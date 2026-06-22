# Laporan Teknis Proyek (Dokumen Kompilasi)

## Daftar Dokumen (Komilasi)
1. **Proposal**
   - `01_Proposal_&_Analisis/Proposal_Teknis.pdf`

2. **Desain**
   - `02_Design_Documents/Architecture_Diagram.pdf`
   - `02_Design_Documents/ERD_Modified.png`
   - `02_Design_Documents/Testing_Plan.pdf`

3. **Manual Penggunaan Fitur Keamanan bagi Administrator**
   - (Dokumen dibuat di file ini sebagai panduan ringkas)

4. **Log Pengujian**
   - `05_Testing/*` (hasil/pengujian otomatis melalui PHPUnit)
   - `JIKA_Paper.md` (ringkasan hasil uji performa autentikasi plaintext vs enkripsi)

---

# Manual Penggunaan Fitur Keamanan (Administrator)

> Catatan: bagian ini adalah panduan operasional berdasarkan implementasi fitur keamanan pada repositori.

## A. Akses Modul Admin (Filament)
1. Login sebagai akun dengan role **admin**.
2. Buka URL dashboard admin.
3. Pastikan Anda dapat mengakses resource (mis. User Resource).

**Validasi yang relevan:**
- `Tests\Feature\Admin\AdminAccessTest.php`

## B. Manajemen Pengguna (Role & Akses)
1. Dari dashboard admin, buka menu **Users**.
2. Verifikasi bahwa pengguna memiliki field **role** yang sesuai (admin/customer/user).
3. (Jika tersedia) kelola status verifikasi pengguna agar fitur akses pengguna sesuai.

## C. Autentikasi dan Verifikasi OTP
1. Saat registrasi user, sistem mengirim OTP ke email.
2. Admin tidak perlu memasukkan OTP (alur OTP berasal dari user), namun admin dapat memverifikasi dampak status `is_verified` melalui proses login.

## D. Verifikasi Signature Pesanan
1. Buka halaman verifikasi signature (endpoint terkait).
2. Masukkan `signature_id`.
3. Sistem mengembalikan status valid/invalid.

## E. Pengujian Performa (Plaintext vs Enkripsi Payload)
1. Jalankan test performa dengan filter:
   - `php artisan test --filter=AuthEncryptionPerformanceTest`
2. Hasil uji dirangkum pada `JIKA_Paper.md` di **Tabel 1**.

---

# Log Pengujian (Ringkasan)

## 1. Test Suite yang tersedia di repositori
- `05_Testing/Feature/Auth/LoginTest.php`
- `05_Testing/Feature/Auth/RegisterTest.php`
- `05_Testing/Feature/Social/WishlistReviewQuestionTest.php`
- `05_Testing/Feature/Admin/AdminAccessTest.php`
- `05_Testing/*` lain sesuai struktur folder

## 2. Test performa autentikasi plaintext vs encrypted payload
- File test: 
  - `03_Source_Code/backend/tests/Feature/Auth/AuthEncryptionPerformanceTest.php`
- Ringkasan hasil: **Tabel 1** pada `JIKA_Paper.md`

