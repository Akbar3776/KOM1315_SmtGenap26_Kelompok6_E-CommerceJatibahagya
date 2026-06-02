<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * Service untuk menangani digital signature pada proses checkout.
 * Menjamin integritas data pesanan dari client ke server.
 */
class DigitalSignatureService
{
    /**
     * Generate digital signature untuk data pesanan.
     *
     * @param array $data Data yang akan ditandatangani
     * @param string|null $privateKey Private key untuk signing
     * @return array Signature dan hash data
     */
    public function generateSignature(array $data, ?string $privateKey = null): array
    {
        // Jika tidak ada private key, generate dari user session atau gunakan default
        $privateKey = $privateKey ?? $this->getDefaultPrivateKey();
        
        // Buat hash dari data pesanan
        $dataHash = $this->hashOrderData($data);
        
        // Buat signature menggunakan private key
        $signature = $this->signData($dataHash, $privateKey);
        
        return [
            'signature' => $signature,
            'data_hash' => $dataHash,
            'algorithm' => 'SHA256',
            'timestamp' => $data['timestamp'] ?? now()->toIso8601String(),
        ];
    }

    /**
     * Verifikasi digital signature pesanan.
     *
     * @param array $signatureData Data signature dari client
     * @param array $orderData Data pesanan yang akan diverifikasi
     * @return bool
     */
    public function verifySignature(array $signatureData, array $orderData): bool
    {
        // Hitung ulang hash dari data pesanan
        $calculatedHash = $this->hashOrderData($orderData);
        
        // Bandingkan hash yang dihitung dengan hash yang ada di signature
        if ($calculatedHash !== $signatureData['data_hash']) {
            return false;
        }
        
        // Verifikasi signature menggunakan public key
        $publicKey = $this->getDefaultPublicKey();
        return $this->verifySignedData($signatureData['data_hash'], $signatureData['signature'], $publicKey);
    }

    /**
     * Hash data pesanan menggunakan SHA-256.
     *
     * @param array $data
     * @return string
     */
    private function hashOrderData(array $data): string
    {
        // Sort keys untuk konsistensi
        ksort($data);
        
        // Encode data ke JSON
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        // Generate hash
        return hash('sha256', $jsonData);
    }

    /**
     * Sign data dengan private key menggunakan RSA.
     *
     * @param string $data
     * @param string $privateKey
     * @return string
     */
    private function signData(string $data, string $privateKey): string
    {
        // Menggunakan Crypt facade untuk encrypt data (simulasi signing)
        // Dalam produksi, gunakan phpseclib atau library kriptografi lainnya
        $signed = Crypt::encryptString($data . '|' . $privateKey);
        
        return base64_encode($signed);
    }

    /**
     * Verify signed data dengan public key.
     *
     * @param string $originalData
     * @param string $signature
     * @param string $publicKey
     * @return bool
     */
    private function verifySignedData(string $originalData, string $signature, string $publicKey): bool
    {
        try {
            $decrypted = base64_decode($signature);
            $decryptedData = Crypt::decryptString($decrypted);
            
            // Parse decrypted data
            $parts = explode('|', $decryptedData);
            if (count($parts) < 2) {
                return false;
            }
            
            // Verifikasi bahwa data asli cocok
            $storedOriginalData = $parts[0];
            $storedKey = $parts[1];
            
            return $storedOriginalData === $originalData && $storedKey === $publicKey;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Generate unique signature ID.
     *
     * @return string
     */
    public function generateSignatureId(): string
    {
        return 'SIG-' . strtoupper(Str::random(16)) . '-' . time();
    }

    /**
     * Get default private key (dalam produksi, ini harus dari secure vault).
     *
     * @return string
     */
    private function getDefaultPrivateKey(): string
    {
        return env('APP_KEY', 'default-private-key-for-signing');
    }

    /**
     * Get default public key.
     *
     * @return string
     */
    private function getDefaultPublicKey(): string
    {
        return env('APP_KEY', 'default-private-key-for-signing');
    }

    /**
     * Tanda tangani data pesanan dan return signature details.
     *
     * @param int $orderId
     * @param float $amount
     * @param string $shippingAddress
     * @param string $userId
     * @return array
     */
    public function signOrderData(int $orderId, float $amount, string $shippingAddress, string $userId): array
    {
        $timestamp = now()->toIso8601String();

        $orderData = [
            'order_id' => $orderId,
            'amount' => number_format($amount, 2, '.', ''),
            'shipping_address' => $shippingAddress,
            'user_id' => $userId,
            'timestamp' => $timestamp,
        ];
        
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
}
