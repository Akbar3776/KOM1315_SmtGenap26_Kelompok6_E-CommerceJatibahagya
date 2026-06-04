# Cara Generate RSA Keys Secara Manual (Tanpa PHP OpenSSL Extension)

Jika `php artisan-rsa-key-generator.php` gagal karena OpenSSL extension tidak aktif, gunakan metode alternatif berikut:

## Metode 1: Menggunakan OpenSSL Command Line (Rekomendasi)

### Langkah 1: Generate Private Key

Buka Command Prompt atau PowerShell, jalankan:

```powershell
cd "c:\Users\M. Syafiq Romadhon\Documents\GitHub\KOM1315_SmtGenap26_Kelompok6_E-CommerceJatibahagya\03_Source_Code\backend"
openssl genrsa -out private.pem 2048
```

### Langkah 2: Generate Public Key dari Private Key

```powershell
openssl rsa -in private.pem -pubout -out public.pem
```

### Langkah 3: Baca Isi Private Key

```powershell
type private.pem
```

Copy seluruh output mulai dari `-----BEGIN RSA PRIVATE KEY-----` sampai `-----END RSA PRIVATE KEY-----`

### Langkah 4: Baca Isi Public Key

```powershell
type public.pem
```

Copy seluruh output mulai dari `-----BEGIN PUBLIC KEY-----` sampai `-----END PUBLIC KEY-----`

---

## Metode 2: Generate Langsung di .env

### Langkah 1: Generate Private Key

```powershell
openssl genrsa 2048 > temp_private.pem
```

### Langkah 2: Convert ke format yang bisa langsung paste ke .env (tanpa header/footer)

```powershell
openssl rsa -in temp_private.pem -pubout 2>nul | findstr /v CERTIFICATE
```

### Langkah 3: Atau gunakan cara paling mudah

1. Buat file `generate_keys.bat` dengan konten berikut:

```batch
@echo off
echo Generating RSA Keys...
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem
echo.
echo === PRIVATE KEY ===
type private.pem
echo.
echo === PUBLIC KEY ===
type public.pem
echo.
echo Keys saved to private.pem and public.pem
pause
```

2. Jalankan: `generate_keys.bat`

---

## Langkah 5: Tambahkan ke .env

Edit file `.env` di folder backend, tambahkan di bagian bawah:

```env
RSA_PRIVATE_KEY=-----BEGIN RSA PRIVATE KEY-----
MIIC.... (paste private key di sini)
-----END RSA PRIVATE KEY-----

RSA_PUBLIC_KEY=-----BEGIN PUBLIC KEY-----
MIIBIj.... (paste public key di sini)
-----END PUBLIC KEY-----
```

**PENTING:**

- Pastikan private key dalam satu baris (tanpa enter/newline)
- atau gunakan format multi-line dengan quotes
- Contoh format multi-line di .env:

```env
RSA_PRIVATE_KEY="-----BEGIN RSA PRIVATE KEY-----
MIIC.....
.....
-----END RSA PRIVATE KEY-----"
```

---

## Verifikasi Installation

1. Jalankan `composer install` untuk install DomPDF dan QR Code library
2. Jalankan `php artisan migrate` untuk membuat tabel invoice_signatures
3. Buat order baru
4. Download invoice PDF dari halaman detail order
5. Buka `/verify-invoice/{id}` untuk verifikasi

---

## Troubleshooting

### "openssl is not recognized as an internal or external command"

Windows tidak memiliki OpenSSL terinstall. Install dengan:

- **Git Bash**: Sudah termasuk OpenSSL
- **Windows Subsystem for Linux (WSL)**: `sudo apt install openssl`
- **Chocolatey**: `choco install openssl`

### Atau gunakan Python untuk generate keys:

```python
from Crypto.PublicKey import RSA
key = RSA.generate(2048)
private_key = key.export_key()
public_key = key.publickey().export_key()
print("PRIVATE:", private_key.decode())
print("PUBLIC:", public_key.decode())
```

Install dengan: `pip install pycryptodome`
