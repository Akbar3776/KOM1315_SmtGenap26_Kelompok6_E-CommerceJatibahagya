<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSignature;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;

class InvoiceVerificationController extends Controller
{
    protected DigitalSignatureService $digitalSignatureService;

    public function __construct()
    {
        $this->digitalSignatureService = app(DigitalSignatureService::class);
    }

    /**
     * Display public invoice verification page
     * Route: /verify-invoice/{id}
     *
     * Alur verifikasi:
     * 1. QR Code -> invoice_signature_id
     * 2. Ambil data order terbaru dari database
     * 3. Hash ulang data order secara deterministik
     * 4. Verifikasi RSA signature menggunakan public key admin
     * 5. Tampilkan hasil VALID / INVALID
     */
    public function show($id)
    {
        $invoiceSignature = InvoiceSignature::with(['order.user', 'signedByAdmin'])
            ->find($id);

        if (!$invoiceSignature) {
            return view('invoices.verification', [
                'valid' => false,
                'reason' => 'Invoice signature tidak ditemukan. ID: ' . $id,
                'invoiceSignature' => null,
                'order' => null,
                'verificationResult' => null,
            ]);
        }

        // Verifikasi signature - ambil order terbaru, hash ulang, verify
        $verificationResult = $this->digitalSignatureService->verifyInvoiceSignature($invoiceSignature);

        $order = $invoiceSignature->order;

        // Update verified_at timestamp jika valid
        if ($verificationResult['valid']) {
            $invoiceSignature->update(['verified_at' => now()]);
        }

        return view('invoices.verification', [
            'valid' => $verificationResult['valid'],
            'reason' => $verificationResult['reason'] ?? null,
            'invoiceSignature' => $invoiceSignature,
            'order' => $order,
            'verificationResult' => $verificationResult,
        ]);
    }

    /**
     * API endpoint untuk verifikasi QR code (JSON response)
     *
     * Payload dari QR: invoice_signature_id (integer)
     * Response: VALID / INVALID dengan detail
     */
    public function verify(Request $request, $id)
    {
        $invoiceSignature = InvoiceSignature::with(['order.user', 'signedByAdmin'])
            ->find($id);

        if (!$invoiceSignature) {
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Invoice signature tidak ditemukan.',
            ], 404);
        }

        // Verifikasi signature - ambil order terbaru, hash ulang, verify
        $verificationResult = $this->digitalSignatureService->verifyInvoiceSignature($invoiceSignature);

        if ($verificationResult['valid']) {
            $invoiceSignature->update(['verified_at' => now()]);

            return response()->json([
                'success' => true,
                'valid' => true,
                'message' => 'VALID',
                'data' => [
                    'signature_id' => $invoiceSignature->id,
                    'order_id' => $invoiceSignature->order_id,
                    'order_code' => $invoiceSignature->order?->order_code,
                    'signed_by' => $invoiceSignature->signedByAdmin?->name ?? 'System',
                    'signed_at' => $invoiceSignature->signed_at?->toIso8601String(),
                    'algorithm' => $invoiceSignature->algorithm,
                    'verified_at' => now()->toIso8601String(),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'valid' => false,
            'message' => 'INVALID',
            'data' => [
                'signature_id' => $invoiceSignature->id,
                'order_id' => $invoiceSignature->order_id,
                'reason' => $verificationResult['reason'] ?? 'Unknown error',
            ],
        ], 400);
    }
}