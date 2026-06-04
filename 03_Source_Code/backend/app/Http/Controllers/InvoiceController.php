<?php

namespace App\Http\Controllers;

use App\Models\InvoiceSignature;
use App\Models\Order;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class InvoiceController extends Controller
{
    protected DigitalSignatureService $digitalSignatureService;

    public function __construct()
    {
        $this->digitalSignatureService = app(DigitalSignatureService::class);
    }

    /**
     * Download invoice PDF for an order
     * User must be the order owner or an admin
     */
    public function download(Request $request, $orderId)
    {
        $order = Order::with(['orderItems.product', 'orderItems.variant', 'userAddress', 'user'])
            ->findOrFail($orderId);

        // Authorization: User can only download their own invoice, admin can download any
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';
        $isOwner = $order->user_id === $user?->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Ensure invoice signature exists
        $invoiceSignature = InvoiceSignature::where('order_id', $order->id)->first();

        if (!$invoiceSignature) {
            // Auto-generate signature if not exists
            $adminId = $isAdmin ? $user->id : null;
            $invoiceSignature = $this->digitalSignatureService->createOrUpdateInvoiceSignature($order, $adminId);
        }

        // Generate QR code with ONLY invoice_signature_id
        // Verification flow: QR -> invoice_signature_id -> get order data -> hash -> verify RSA signature
        $qrPayload = (string) $invoiceSignature->id;
        $qrImage = $this->generateQrCode($qrPayload);

        // Prepare order data for PDF
        $orderData = $this->digitalSignatureService->makeOrderDataFromOrder($order);

        // Store info
        $storeName = config('app.store_name', 'Jatiba Hagya');
        $storeAddress = config('app.store_address', 'Jl. Toko No. 1, Jakarta');
        $storePhone = config('app.store_phone', '021-12345678');
        $storeEmail = config('app.store_email', 'contact@jatibahagya.com');

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'order' => $order,
            'orderData' => $orderData,
            'invoiceSignature' => $invoiceSignature,
            'qrImage' => $qrImage,
            'storeName' => $storeName,
            'storeAddress' => $storeAddress,
            'storePhone' => $storePhone,
            'storeEmail' => $storeEmail,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Invoice_' . $order->order_code . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate QR code as base64 encoded PNG
     * QR contains only: invoice_signature_id
     */
    private function generateQrCode(string $data): string
    {
        $qrCode = new QrCode($data);
        $qrCode->setSize(200);
        $qrCode->setMargin(10);
        $qrCode->setErrorCorrectionLevel('H');

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }

    /**
     * Generate invoice signature for an order (re-sign)
     */
    public function regenerateSignature(Request $request, $orderId)
    {
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';

        if (!$isAdmin) {
            abort(403, 'Hanya admin yang dapat menandatangani ulang invoice.');
        }

        $order = Order::findOrFail($orderId);

        $invoiceSignature = $this->digitalSignatureService->createOrUpdateInvoiceSignature($order, $user->id);

        return redirect()->back()->with('success', 'Signature invoice berhasil diperbarui.');
    }
}