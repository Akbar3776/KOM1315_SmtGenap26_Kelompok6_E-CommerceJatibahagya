<?php
/**
 * RSA Key Generator Script
 * 
 * Run this script once to generate RSA key pair for invoice signing.
 * 
 * Usage: php artisan-rsa-key-generator.php
 * 
 * This will generate:
 * - Private key for signing (store securely in .env as RSA_PRIVATE_KEY)
 * - Public key for verification (store in .env as RSA_PUBLIC_KEY)
 * 
 * Alternative: If this script fails, you can use OpenSSL command line:
 *   openssl genrsa -out private.pem 2048
 *   openssl rsa -in private.pem -pubout -out public.pem
 */

$config = [
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

echo "================================================\n";
echo "  RSA Key Pair Generator for Invoice Signing\n";
echo "================================================\n\n";

echo "Generating RSA-2048 key pair...\n";

// Check if openssl extension is loaded
if (!extension_loaded('openssl')) {
    echo "\n[ERROR] OpenSSL extension is not loaded!\n\n";
    echo "Please enable OpenSSL in your PHP installation.\n";
    echo "For Windows: Uncomment or add 'extension=openssl' in php.ini\n";
    echo "For Linux: Install php-openssl package\n\n";
    
    echo "ALTERNATIVE METHOD - Using OpenSSL CLI:\n";
    echo "----------------------------------------\n";
    echo "1. Open terminal and run:\n";
    echo "   openssl genrsa -out private.pem 2048\n";
    echo "   openssl rsa -in private.pem -pubout -out public.pem\n\n";
    echo "2. Read the content of the files:\n";
    echo "   cat private.pem\n";
    echo "   cat public.pem\n\n";
    echo "3. Copy the contents to your .env file as RSA_PRIVATE_KEY and RSA_PUBLIC_KEY\n";
    
    exit(1);
}

try {
    // Try to generate key pair
    $keyPair = openssl_pkey_new($config);

    if (!$keyPair) {
        echo "\n[ERROR] Failed to generate key pair.\n";
        echo "OpenSSL error: " . openssl_error_string() . "\n\n";
        
        echo "ALTERNATIVE METHOD - Using OpenSSL CLI:\n";
        echo "----------------------------------------\n";
        echo "1. Open terminal and run:\n";
        echo "   openssl genrsa -out private.pem 2048\n";
        echo "   openssl rsa -in private.pem -pubout -out public.pem\n\n";
        echo "2. Read the content of the files:\n";
        echo "   For Windows PowerShell:\n";
        echo "   Get-Content private.pem\n";
        echo "   Get-Content public.pem\n\n";
        echo "3. Copy the contents to your .env file.\n";
        
        exit(1);
    }

    // Export private key
    $privateKey = null;
    openssl_pkey_export($keyPair, $privateKey);

    // Get public key
    $publicKeyDetails = openssl_pkey_get_details($keyPair);
    $publicKey = $publicKeyDetails['key'];

    echo "\n[SUCCESS] RSA key pair generated successfully!\n\n";

    echo "=== PRIVATE KEY (RSA_PRIVATE_KEY) ===\n";
    echo "Copy this entire section to your .env file:\n\n";
    echo "RSA_PRIVATE_KEY=\"-----BEGIN RSA PRIVATE KEY-----\n";
    $privateKeyLines = explode("\n", wordwrap($privateKey, 64, "\n", true));
    foreach ($privateKeyLines as $line) {
        echo $line . "\\n";
    }
    echo "-----END RSA PRIVATE KEY-----\"\n\n";

    echo "=== PUBLIC KEY (RSA_PUBLIC_KEY) ===\n";
    echo "Copy this entire section to your .env file:\n\n";
    echo "RSA_PUBLIC_KEY=\"-----BEGIN RSA PUBLIC KEY-----\n";
    $publicKeyLines = explode("\n", wordwrap($publicKey, 64, "\n", true));
    foreach ($publicKeyLines as $line) {
        echo $line . "\\n";
    }
    echo "-----END RSA PUBLIC KEY-----\"\n\n";

    // Save keys to files for backup
    file_put_contents(__DIR__ . '/storage/private_key.pem', $privateKey);
    file_put_contents(__DIR__ . '/storage/public_key.pem', $publicKey);
    
    echo "=== KEYS SAVED ===\n";
    echo "Private key saved to: storage/private_key.pem\n";
    echo "Public key saved to: storage/public_key.pem\n";
    echo "(Please keep private key secure and never commit it to version control!)\n";

    echo "\n=== NEXT STEPS ===\n";
    echo "1. Copy the RSA_PRIVATE_KEY and RSA_PUBLIC_KEY to your .env file\n";
    echo "2. Run: php artisan migrate\n";
    echo "3. Test the invoice feature!\n";

} catch (Exception $e) {
    echo "\n[ERROR] Exception: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nDone!\n";