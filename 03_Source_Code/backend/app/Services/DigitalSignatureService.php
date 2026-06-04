<?php

namespace App\Services;

use App\Models\InvoiceSignature;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Str;

class DigitalSignatureService
{
    private const ALGORITHM = 'RSA-SHA256';
    private const KEY_SIZE = 2048;

    /**
     * Generate RSA key pair
     */
    public function generateKeyPair(): array
    {
        $config = [
            'private_key_bits' => self::KEY_SIZE,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        $keyPair = openssl_pkey_new($config);

        openssl_pkey_export($keyPair, $privateKey);
        $publicKey = openssl_pkey_get_details($keyPair)['key'];

        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey,
        ];
    }

    /**
     * Sign data using RSA private key
     */
    public function signData(string $data, string $privateKey): string
    {
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($signature);
    }

    /**
     * Verify signature using RSA public key
     */
    public function verifySignature(string $data, string $signature, string $publicKey): bool
    {
        $decodedSignature = base64_decode($signature, true);

        if ($decodedSignature === false) {
            return false;
        }

        return openssl_verify($data, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    /**
     * Hash order data deterministically for canonical representation
     */
    public function hashOrderData(array $data): string
    {
        $this->sortKeysRecursively($data);
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        return hash('sha256', $jsonData);
    }

    /**
     * Build canonical order data from Order model
     */
    public function makeOrderDataFromOrder(Order $order): array
    {
        $order->loadMissing(['orderItems', 'user']);

        $orderItems = $order->orderItems
            ->sortBy([
                ['created_at', 'asc'],
                ['product_id', 'asc'],
                ['variant_id', 'asc'],
            ])
            ->values()
            ->map(fn($item) => [
                'product_id' => $item->product_id,
                'quantity' => (int) $item->quantity,
                'price_per_item' => $this->formatMoney($item->price_per_item),
                'total_price' => $this->formatMoney($item->total_price),
                'created_at' => $this->formatDateTime($item->created_at),
                'variant_id' => $item->variant_id,
            ])
            ->all();

        return [
            'order' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'address_id' => $order->user_address_id,
                'order_code' => (string) $order->order_code,
                'total_order' => $this->formatMoney($order->total_order),
                'total_shipping' => $this->formatMoney($order->total_shipping),
                'total_fee' => $this->formatMoney($order->total_fee),
                'amount' => $this->formatMoney($order->amount),
                'shipping_address' => (string) $order->shipping_address,
                'notes' => $order->notes,
                'created_at' => $this->formatDateTime($order->created_at),
                'status' => $order->status,
                'payment_status' => $order->payment_status,
            ],
            'order_items' => $orderItems,
        ];
    }

    /**
     * Generate invoice signature for an order
     */
    public function signOrder(Order $order): array
    {
        $orderData = $this->makeOrderDataFromOrder($order);
        $canonicalJson = $this->getCanonicalJson($orderData);
        $hashValue = hash('sha256', $canonicalJson);

        $privateKey = $this->getPrivateKey();
        $signature = $this->signData($hashValue, $privateKey);
        $publicKey = $this->getPublicKey();

        return [
            'signature_id' => 'SIG-' . strtoupper(Str::random(16)) . '-' . time(),
            'order_data' => $orderData,
            'hash_value' => $hashValue,
            'signature' => $signature,
            'algorithm' => self::ALGORITHM,
            'canonical_json' => $canonicalJson,
        ];
    }

    /**
     * Create or update invoice signature for order
     */
    public function createOrUpdateInvoiceSignature(Order $order, ?int $adminId = null): InvoiceSignature
    {
        $signatureData = $this->signOrder($order);

        // Delete existing signatures for this order (if any)
        InvoiceSignature::where('order_id', $order->id)->delete();

        $invoiceSignature = InvoiceSignature::create([
            'order_id' => $order->id,
            'signature' => $signatureData['signature'],
            'hash_value' => $signatureData['hash_value'],
            'algorithm' => $signatureData['algorithm'],
            'signed_by_admin_id' => $adminId,
        ]);

        return $invoiceSignature;
    }

    /**
     * Verify invoice signature
     */
    public function verifyInvoiceSignature(InvoiceSignature $invoiceSignature, ?Order $currentOrder = null): array
    {
        $order = $currentOrder ?? $invoiceSignature->order;

        if (!$order) {
            return [
                'valid' => false,
                'reason' => 'Order tidak ditemukan',
            ];
        }

        // Regenerate hash from current order data
        $currentOrderData = $this->makeOrderDataFromOrder($order);
        $canonicalJson = $this->getCanonicalJson($currentOrderData);
        $currentHash = hash('sha256', $canonicalJson);

        // Verify hash matches
        if (!hash_equals($invoiceSignature->hash_value, $currentHash)) {
            return [
                'valid' => false,
                'reason' => 'Data order telah dimodifikasi - hash tidak cocok',
                'stored_hash' => $invoiceSignature->hash_value,
                'current_hash' => $currentHash,
            ];
        }

        // Verify signature using public key
        $publicKey = $this->getPublicKey();
        $isValidSignature = $this->verifySignature(
            $invoiceSignature->hash_value,
            $invoiceSignature->signature,
            $publicKey
        );

        if (!$isValidSignature) {
            return [
                'valid' => false,
                'reason' => 'Signature tidak valid - tidak dapat diverifikasi dengan public key admin',
            ];
        }

        return [
            'valid' => true,
            'reason' => 'Signature valid dan data order intact',
        ];
    }

    /**
     * Get QR payload for invoice verification
     */
    public function getQrPayload(InvoiceSignature $invoiceSignature): array
    {
        return [
            'invoice_signature_id' => $invoiceSignature->id,
            'order_id' => $invoiceSignature->order_id,
            'signature' => $invoiceSignature->signature,
        ];
    }

    /**
     * Get QR payload as JSON string
     */
    public function getQrPayloadString(InvoiceSignature $invoiceSignature): string
    {
        return json_encode($this->getQrPayload($invoiceSignature), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get private key from config/env
     */
    private function getPrivateKey(): string
    {
        $key = env('RSA_PRIVATE_KEY');

        if (!$key) {
            throw new \RuntimeException('RSA_PRIVATE_KEY not configured in .env');
        }

        return $key;
    }

    /**
     * Get public key from config/env
     */
    private function getPublicKey(): string
    {
        $key = env('RSA_PUBLIC_KEY');

        if (!$key) {
            throw new \RuntimeException('RSA_PUBLIC_KEY not configured in .env');
        }

        return $key;
    }

    /**
     * Get canonical JSON representation
     */
    private function getCanonicalJson(array $data): string
    {
        $this->sortKeysRecursively($data);

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS);
    }

    /**
     * Sort array keys recursively
     */
    private function sortKeysRecursively(array &$data): void
    {
        ksort($data);

        foreach ($data as &$value) {
            if (is_array($value)) {
                $this->sortKeysRecursively($value);
            }
        }
    }

    /**
     * Format money value
     */
    private function formatMoney(float|string|null $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }

    /**
     * Format datetime value
     */
    private function formatDateTime($value): ?string
    {
        return $value?->toIso8601String();
    }
}