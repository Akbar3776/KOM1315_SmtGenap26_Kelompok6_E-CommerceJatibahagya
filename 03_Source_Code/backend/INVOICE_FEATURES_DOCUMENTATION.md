# Invoice PDF dengan Digital Signature dan QR Verification - Dokumentasi Implementasi

## 📋 Ringkasan Fitur

Fitur Invoice PDF dengan Digital Signature dan QR Verification telah diimplementasikan untuk project Laravel e-commerce Jatiba Hagya.

---

## 📁 FILE YANG DIBUAT (BARU)

### 1. Model

- **`app/Models/InvoiceSignature.php`** - Model untuk menyimpan data signature invoice

### 2. Controller

- **`app/Http/Controllers/InvoiceController.php`** - Controller untuk download PDF invoice
- **`app/Http/Controllers/InvoiceVerificationController.php`** - Controller untuk verifikasi publik invoice

### 3. Middleware

- **`app/Http/Middleware/AdminMiddleware.php`** - Middleware untuk proteksi route admin

### 4. Migration

- **`database/migrations/2025_06_04_000000_create_invoice_signatures_table.php`** - Tabel invoice_signatures

### 5. View

- **`resources/views/invoices/pdf.blade.php`** - Template PDF invoice
- **`resources/views/invoices/verification.blade.php`** - Halaman verifikasi publik
- **`resources/views/invoices/download-button.blade.php`** - Component tombol download

### 6. Script & Config

- **`artisan-rsa-key-generator.php`** - Script untuk generate RSA key pair
- **`composer.json`** (updated) - Menambahkan library DomPDF dan QR Code

---

## 📝 FILE YANG DIMODIFIKASI

### 1. Services

- **`app/Services/DigitalSignatureService.php`** - Diubah dari HMAC ke RSA asymmetric

### 2. Routes

- **`routes/web.php`** - Ditambahkan route baru untuk invoice

### 3. Middleware

- **`bootstrap/app.php`** - Ditambahkan middleware alias 'admin'

### 4. Controllers

- **`app/Http/Controllers/OrderController.php`** - Ditambahkan auto-generate invoice signature

### 5. Views

- **`resources/views/orders/detail.blade.php`** - Ditambahkan tombol Download Invoice PDF

### 6. Config

- **`.env.example`** - Ditambahkan placeholder RSA keys

---

## 🔐 TABLE STRUCTURE: `invoice_signatures`

| Kolom              | Tipe        | Deskripsi                      |
| ------------------ | ----------- | ------------------------------ |
| id                 | bigint      | Primary key                    |
| order_id           | bigint      | Foreign key ke orders          |
| hash_value         | string(64)  | SHA-256 hash dari data order   |
| signature          | text        | RSA signature (base64 encoded) |
| algorithm          | string(20)  | RSA-SHA256                     |
| signed_by_admin_id | bigint/null | ID admin yang menandatangani   |
| signed_at          | timestamp   | Waktu penandatanganan          |
| timestamps         | timestamps  | created_at, updated_at         |

---

## 🚀 LANGKAH INSTALASI

### 1. Install Dependencies

```bash
cd 03_Source_Code/backend
composer install
```

### 2. Generate RSA Key Pair

```bash
php artisan-rsa-key-generator.php
```

Script ini akan menampilkan output seperti:

```
=== PRIVATE KEY (RSA_PRIVATE_KEY) ===
-----BEGIN RSA PRIVATE KEY-----
... (private key content) ...
-----END RSA PRIVATE KEY-----

=== PUBLIC KEY (RSA_PUBLIC_KEY) -----
-----BEGIN RSA PUBLIC KEY-----
... (public key content) ...
-----END RSA PUBLIC KEY-----
```

### 3. Konfigurasi .env

Tambahkan keys ke file `.env`:

```env
RSA_PRIVATE_KEY=-----BEGIN RSA PRIVATE KEY-----
... (paste private key) ...
-----END RSA PRIVATE KEY-----

RSA_PUBLIC_KEY=-----BEGIN RSA PUBLIC KEY-----
... (paste public key) ...
-----END RSA PUBLIC KEY-----
```

### 4. Jalankan Migration

```bash
php artisan migrate
```

---

## 📡 ROUTE BARU

| Method | URI                  | Description               | Auth  |
| ------ | -------------------- | ------------------------- | ----- |
| GET    | /verify-invoice/{id} | Halaman verifikasi publik | No    |
| POST   | /verify-invoice/{id} | API verifikasi JSON       | No    |
| GET    | /orders/{id}/invoice | Download PDF invoice      | Yes   |
| POST   | /orders/{id}/resign  | Regenerate signature      | Admin |

---

## 🔑 FITUR UTAMA

### 1. Digital Signature (RSA-SHA256)

- Data order di-hash dengan SHA-256 secara deterministik
- Hash di-sign dengan private key admin menggunakan RSA
- Signature dapat diverifikasi dengan public key

### 2. QR Code Verification

- QR Code berisi JSON payload:

```json
{
    "invoice_signature_id": "...",
    "order_id": "...",
    "signature": "..."
}
```

- QR dapat discan dari PDF invoice

### 3. Public Verification Page

- URL: `/verify-invoice/{id}`
- Tidak memerlukan login
- Menampilkan hasil verifikasi VALID/INVALID
- Menampilkan detail: Order Code, Signed By, Signed At, Algorithm

### 4. PDF Invoice

- Nama toko dan info kontak
- Nomor invoice dan order code
- Data customer dan shipping address
- Daftar item dengan quantity dan harga
- Total order, shipping fee, fee, grand total
- Status order dan payment status
- QR Code untuk verifikasi
- Digital signature info

---

## 🧪 LANGKAH TESTING MANUAL

### Testing 1: Generate Keys

```bash
php artisan-rsa-key-generator.php
```

### Testing 2: Checkout & Generate Invoice

1. Login sebagai user
2. Tambahkan produk ke cart
3. Checkout
4. Invoice signature akan auto-generate

### Testing 3: Download Invoice PDF

1. Login sebagai user
2. Buka halaman orders
3. Klik "Detail" pada order
4. Klik tombol "Download Invoice PDF"
5. PDF akan terdownload

### Testing 4: Verifikasi Invoice

1. Buka URL `/verify-invoice/{id}` (dari QR atau manual)
2. Lihat hasil verifikasi:
    - **VALID**: Menampilkan detail order dan signature
    - **INVALID**: Menampilkan alasan kegagalan

### Testing 5: Admin Re-sign

1. Login sebagai admin
2. Buka halaman admin untuk orders
3. Klik "Re-sign" untuk generate signature baru

---

## 🔒 KEAMANAN

1. **Asymmetric Cryptography**: Menggunakan RSA-2048 untuk digital signature
2. **Deterministic Hashing**: JSON canonical format untuk konsistensi hash
3. **Authorization**: User hanya bisa download invoice sendiri, admin bisa semua
4. **Public Verification**: Siapa saja bisa verifikasi invoice via QR/URL
5. **Audit Trail**: Menyimpan timestamp dan admin ID yang menandatangani

---

## 📦 DEPENDENCIES BARU

```json
{
    "barryvdh/laravel-dompdf": "^3.1",
    "endroid/qr-code": "^5.1"
}
```

---

## ⚠️ CATATAN PENTING

1. **Private Key Security**: Simpan RSA_PRIVATE_KEY dengan aman, jangan dishare
2. **Public Key Distribution**: RSA_PUBLIC_KEY bisa didistribusikan untuk verifikasi
3. **Backward Compatibility**: OrderSignature lama masih berfungsi (HMAC-based)
4. **Invoice Signature New**: InvoiceSignature baru menggunakan RSA

---

## 🐛 TROUBLESHOOTING

### Error: "RSA_PRIVATE_KEY not configured"

- Pastikan RSA keys sudah ditambahkan ke `.env`

### Error: "openssl_pkey_new() failed"

- Pastikan OpenSSL extension PHP aktif

### Error: PDF tidak terdownload

- Pastikan DomPDF sudah terinstall: `composer install`
- Pastikan view `invoices/pdf.blade.php` ada

### QR Code tidak bisa discan

- Pastikan QR menggunakan error correction level High
- Pastikan ukuran QR cukup besar (minimal 150x150 px)

---

## 📞 DUKUNGAN

Jika ada pertanyaan atau masalah, silakan buat issue di repository GitHub.
