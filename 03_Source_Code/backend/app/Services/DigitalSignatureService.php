<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class DigitalSignatureService
{
    public function generateSignature(array $data, ?string $privateKey = null): array
    {
        $privateKey = $privateKey ?? $this->getDefaultPrivateKey();
        $dataHash = $this->hashOrderData($data);
        $signature = $this->signData($dataHash, $privateKey);
        
        return [
            'signature' => $signature,
            'data_hash' => $dataHash,
            'algorithm' => 'HMAC-SHA256',
            'timestamp' => now()->toIso8601String(),
        ];
    }

    public function verifySignature(array $signatureData, array $orderData): bool
    {
        if (empty($signatureData['data_hash']) || empty($signatureData['signature'])) {
            return false;
        }

        $calculatedHash = $this->hashOrderData($orderData);
        
        if (!hash_equals($calculatedHash, $signatureData['data_hash'])) {
            return false;
        }
        
        $publicKey = $this->getDefaultPublicKey();
        return $this->verifySignedData($signatureData['data_hash'], $signatureData['signature'], $publicKey);
    }

    public function hashOrderData(array $data): string
    {
        $this->sortKeysRecursively($data);
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        return hash('sha256', $jsonData);
    }

    private function signData(string $data, string $privateKey): string
    {
        return hash_hmac('sha256', $data, $this->normalizeKey($privateKey));
    }

    private function verifySignedData(string $originalData, string $signature, string $publicKey): bool
    {
        $expectedSignature = hash_hmac('sha256', $originalData, $this->normalizeKey($publicKey));

        if (hash_equals($expectedSignature, $signature)) {
            return true;
        }

        try {
            $decrypted = base64_decode($signature);
            $decryptedData = Crypt::decryptString($decrypted);
            $parts = explode('|', $decryptedData, 2);
            if (count($parts) < 2) {
                return false;
            }
            $storedOriginalData = $parts[0];
            $storedKey = $parts[1];
            return hash_equals($storedOriginalData, $originalData) && hash_equals($storedKey, $publicKey);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function generateSignatureId(): string
    {
        return 'SIG-' . strtoupper(Str::random(16)) . '-' . time();
    }

    private function getDefaultPrivateKey(): string
    {
        return env('APP_KEY', 'default-private-key-for-signing');
    }

    private function getDefaultPublicKey(): string
    {
        return env('APP_KEY', 'default-private-key-for-signing');
    }

    public function signOrder(Order $order): array
    {
        $orderData = $this->makeOrderDataFromOrder($order);
        $signatureResult = $this->generateSignature($orderData);

        return [
            'signature_id' => $this->generateSignatureId(),
            'signature' => $signatureResult['signature'],
            'data_hash' => $signatureResult['data_hash'],
            'algorithm' => $signatureResult['algorithm'],
            'signed_at' => $signatureResult['timestamp'],
            'order_data' => $orderData,
        ];
    }

    public function makeOrderData(
        Order $order
    ): array {
        return $this->makeOrderDataFromOrder($order);
    }

    public function makeOrderDataFromOrder(Order $order, ?string $timestamp = null): array
    {
        unset($timestamp);

        $order->loadMissing('orderItems');

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
            ],
            'order_items' => $order->orderItems
                ->sortBy([
                    ['created_at', 'asc'],
                    ['product_id', 'asc'],
                    ['variant_id', 'asc'],
                ])
                ->values()
                ->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_per_item' => $this->formatMoney($item->price_per_item),
                    'total_price' => $this->formatMoney($item->total_price),
                    'created_at' => $this->formatDateTime($item->created_at),
                    'variant_id' => $item->variant_id,
                ])
                ->all(),
        ];
    }

    private function normalizeKey(string $key): string
    {
        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);

            if ($decoded !== false) {
                return $decoded;
            }
        }

        return $key;
    }

    private function sortKeysRecursively(array &$data): void
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $this->sortKeysRecursively($value);
            }
        }

        ksort($data);
    }

    private function formatMoney(float|string|null $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }

    private function formatDateTime($value): ?string
    {
        return $value?->toIso8601String();
    }

}
