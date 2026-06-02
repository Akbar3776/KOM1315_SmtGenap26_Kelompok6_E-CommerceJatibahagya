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
        $data['timestamp'] = $data['timestamp'] ?? now()->toIso8601String();
        $dataHash = $this->hashOrderData($data);
        $signature = $this->signData($dataHash, $privateKey);
        
        return [
            'signature' => $signature,
            'data_hash' => $dataHash,
            'algorithm' => 'HMAC-SHA256',
            'timestamp' => $data['timestamp'],
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

    public function signOrderData(int $orderId, float $amount, string $shippingAddress, string $userId): array
    {
        $timestamp = now()->toIso8601String();

        $orderData = $this->makeOrderData($orderId, $amount, $shippingAddress, $userId, $timestamp);
        
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
        int $orderId,
        float|string $amount,
        ?string $shippingAddress,
        int|string $userId,
        ?string $timestamp = null
    ): array {
        return [
            'order_id' => $orderId,
            'amount' => number_format((float) $amount, 2, '.', ''),
            'shipping_address' => (string) $shippingAddress,
            'user_id' => (string) $userId,
            'timestamp' => $timestamp ?? now()->toIso8601String(),
        ];
    }

    public function makeOrderDataFromOrder(Order $order, ?string $timestamp = null): array
    {
        return $this->makeOrderData(
            $order->id,
            $order->amount,
            $order->shipping_address,
            $order->user_id,
            $timestamp
        );
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
}
