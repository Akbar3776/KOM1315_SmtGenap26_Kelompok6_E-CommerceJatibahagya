<?php

namespace App\Http\Controllers;

use App\Models\OrderSignature;
use App\Services\DigitalSignatureService;
use Illuminate\Http\Request;

class SignatureVerificationController extends Controller
{
    protected DigitalSignatureService $digitalSignatureService;

    public function __construct()
    {
        $this->digitalSignatureService = new DigitalSignatureService();
    }

    public function verify(Request $request)
    {
        $request->validate([
            'signature_id' => 'required|string',
        ]);

        $signature = OrderSignature::where('signature_id', $request->signature_id)
            ->with('order')
            ->first();

        if (!$signature) {
            return response()->json([
                'success' => false,
                'message' => 'Signature tidak ditemukan',
            ], 404);
        }

        if (!$signature->order) {
            return response()->json([
                'success' => false,
                'message' => 'Order untuk signature ini tidak ditemukan',
            ], 404);
        }

        $signedOrderData = $signature->order_data ?? [];
        $timestamp = $signedOrderData['timestamp'] ?? $signature->signed_at?->toIso8601String();
        $currentOrderData = $this->digitalSignatureService->makeOrderDataFromOrder($signature->order, $timestamp);

        $isValid = $this->digitalSignatureService->verifySignature(
            [
                'signature' => $signature->signature,
                'data_hash' => $signature->data_hash,
            ],
            $currentOrderData
        );

        if ($isValid) {
            $signature->update(['verified_at' => now()]);
            $signature->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Signature valid',
                'data' => [
                    'signature_id' => $signature->signature_id,
                    'order_id' => $signature->order_id,
                    'signed_at' => $signature->signed_at,
                    'verified_at' => $signature->verified_at?->toIso8601String(),
                    'algorithm' => $signature->algorithm,
                    'integrity' => 'verified',
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Signature tidak valid - data mungkin telah dimodifikasi',
            'data' => [
                'signature_id' => $signature->signature_id,
                'integrity' => 'compromised',
            ],
        ], 400);
    }

    public function show($signatureId)
    {
        $signature = OrderSignature::where('signature_id', $signatureId)
            ->with('order')
            ->first();

        if (!$signature) {
            abort(404, 'Signature tidak ditemukan');
        }

        return view('signature.verify', compact('signature'));
    }
}
