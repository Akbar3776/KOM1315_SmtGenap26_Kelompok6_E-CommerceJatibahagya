# E-Commerce Jatibahagya

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Filament-3.x-FDAE4B?style=for-the-badge&logo=laravel&logoColor=white" alt="Filament">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
  <img src="https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

<p align="center">
  <strong>Proyek Tugas Akhir · KOM1315 Keamanan Jaringan Komputer</strong><br>
  Semester Genap 2025/2026 · Kelompok 6
</p>

---

## Deskripsi Proyek

**E-Commerce Jatibahagya** adalah platform belanja online berbasis web yang dibangun menggunakan framework **Laravel 11** dengan implementasi fitur keamanan meliputi enkripsi data, hashing & salting password, verifikasi OTP, serta autentikasi multi-level (AAA - Authentication, Authorization, Accounting).

Proyek ini dikembangkan sebagai luaran mata kuliah **KOM1315 - Keamanan Jaringan Komputer** dengan fokus pada aspek keamanan sistem e-commerce mulai dari manajemen kunci kriptografi, komunikasi aman, hingga non-repudiation melalui digital signature.

### Fitur Utama

| Fitur | Keterangan |
|---|---|
| Autentikasi & Verifikasi OTP | Registrasi dengan verifikasi email via kode OTP |
| Keranjang Belanja | Mendukung produk dengan varian (warna, ukuran, dll.) |
| Wishlist | Simpan produk favorit |
| Manajemen Produk | Kategori hierarki, merek, atribut & varian produk |
| Kupon Diskon | Tipe persentase dan nominal tetap |
| Pembayaran | Integrasi manajemen transaksi & status pembayaran |
| Pengiriman | Tracking pengiriman dengan estimasi waktu |
| Ulasan & Q&A | Review produk dan tanya jawab dengan admin |
| Wilayah Indonesia | Provinsi, kabupaten, kecamatan, desa (IndoRegion) |
| Panel Admin | Berbasis Filament 3 dengan manajemen multi-peran |

### Arsitektur & Tech Stack

```
Frontend    : Bootstrap 5, TailwindCSS 3, Vite 6, Sass
Backend     : Laravel 11 (PHP 8.2+)
Admin Panel : Filament 3
Database    : MySQL 8.4 / SQLite (development)
Auth        : Laravel UI + OTP Verification
Wilayah     : azishapidin/indoregion + laravolt/indonesia
```

### Struktur Proyek

```
KOM1315_SmtGenap26_Kelompok6_E-CommerceJatibahagya/
├── 01_Proposal_&_Analisis/       # Luaran Pertemuan 1-2
│   ├── Proposal_Teknis.pdf       # Deskripsi sistem & identifikasi aset
│   └── Threat_Modeling.pdf       # Hasil vulnerability assessment awal
├── 02_Design_Documents/          # Luaran Pertemuan 3-4 & 6
│   ├── ERD_Modified.html         # Skema database (buka di browser)
│   ├── Architecture_Diagram.pdf  # Protokol manajemen kunci & komunikasi
│   └── Testing_Plan.pdf          # Rencana uji integrasi protokol AAA
├── 03_Source_Code/               # Luaran Pertemuan 5, 9, 10
│   ├── app/                      # Logic aplikasi (Models, Controllers, dll.)
│   ├── database/                 # Migrations & Seeders
│   └── resources/                # Views & assets frontend
├── 04_Reports_&_Paper/           # Luaran Monitoring & Akhir
│   ├── Monitoring_P7/            # Progress Report & Video Demo
│   ├── Final_Technical_Report/   # Laporan teknis kompilasi
│   └── Scientific_Paper/         # Paper format JIKA & Bukti Submit/LoA
└── README.md
```

---

## Persyaratan Sistem

Pastikan perangkat Anda memenuhi kebutuhan berikut sebelum instalasi:

| Kebutuhan | Versi Minimum |
|---|---|
| PHP | 8.2 atau lebih baru |
| Composer | 2.x |
| Node.js | 18.x atau lebih baru |
| NPM | 9.x atau lebih baru |
| MySQL | 8.0+ (atau SQLite untuk dev) |
| Git | Versi terbaru |

---

## Panduan Instalasi

### Langkah 1 - Clone Repositori

```bash
git clone https://github.com/<username>/KOM1315_SmtGenap26_Kelompok6_E-CommerceJatibahagya.git
cd KOM1315_SmtGenap26_Kelompok6_E-CommerceJatibahagya/03_Source_Code
```

### Langkah 2 - Install Dependensi PHP

```bash
composer install
```

### Langkah 3 - Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian buka file `.env` dan sesuaikan konfigurasi database:

```dotenv
APP_NAME="E-Commerce Jatibahagya"
APP_URL=http://localhost:8000

# Gunakan MySQL (rekomendasi untuk production):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_jatibahagya
DB_USERNAME=root
DB_PASSWORD=your_password

# Konfigurasi Mail (untuk OTP):
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS="noreply@jatibahagya.com"
MAIL_FROM_NAME="E-Commerce Jatibahagya"
```

> **Catatan:** Untuk development cepat, biarkan `DB_CONNECTION=sqlite` dan lewati pembuatan database MySQL.

### Langkah 4 - Generate Application Key

```bash
php artisan key:generate
```

### Langkah 5 - Buat Database (jika menggunakan MySQL)

Buat database terlebih dahulu di MySQL:

```sql
CREATE DATABASE ecommerce_jatibahagya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 6 - Jalankan Migrasi & Seeder

```bash
# Jalankan migrasi tabel
php artisan migrate

# (Opsional) Isi data awal / dummy data
php artisan db:seed
```

### Langkah 7 - Publish Data Wilayah Indonesia

```bash
php artisan indoregion:publish
```

### Langkah 8 - Install Dependensi Frontend

```bash
npm install
```

### Langkah 9 - Build Assets Frontend

Untuk **development** (dengan hot-reload):

```bash
npm run dev
```

Untuk **production**:

```bash
npm run build
```

### Langkah 10 - Buat Storage Link

```bash
php artisan storage:link
```

### Langkah 11 - Jalankan Aplikasi

```bash
php artisan serve
```

Akses aplikasi di browser: **http://localhost:8000**

---

## Menjalankan Semua Layanan Sekaligus

Gunakan perintah berikut untuk menjalankan server, queue, log, dan Vite secara bersamaan:

```bash
composer run dev
```

Perintah ini akan menjalankan:
- `php artisan serve` - Web server
- `php artisan queue:listen` - Background job processor
- `php artisan pail` - Log viewer
- `npm run dev` - Vite HMR

---

## Akses Panel Admin (Filament)

Panel admin dapat diakses di: **http://localhost:8000/admin**

Buat akun admin pertama via Artisan:

```bash
php artisan make:filament-user
```

Ikuti prompt untuk mengisi nama, email, dan password.

---

## Struktur Database

Proyek menggunakan **18 tabel utama**. Lihat ERD lengkap di:

[`02_Design_Documents/ERD_Modified.html`](02_Design_Documents/ERD_Modified.html) *(buka di browser)*

| Kelompok | Tabel |
|---|---|
| Pengguna & Auth | `users`, `user_addresses`, `admins` |
| Transaksi | `orders`, `order_items`, `payments`, `shippings`, `coupons` |
| Produk | `products`, `categories`, `brands`, `attributes`, `attribute_values`, `product_variants`, `product_variant_values` |
| Sosial | `carts`, `wishlists`, `reviews`, `product_questions` |
| Wilayah | `provinces`, `regencies`, `districts`, `villages` |
| Sistem | `settings` |

---

## Fitur Keamanan

Implementasi keamanan sesuai protokol mata kuliah KOM1315:

- **Hashing & Salting** - Password di-hash menggunakan Bcrypt (rounds: 12)
- **OTP Verification** - Verifikasi email saat registrasi via kode OTP berbatas waktu
- **Enkripsi Kolom Sensitif** - Data sensitif terenkripsi di database
- **CSRF Protection** - Semua form dilindungi token CSRF bawaan Laravel
- **XSS Prevention** - Output di-escape secara otomatis oleh Blade templating
- **Role-Based Access Control** - Multi-level: `super_admin`, `staff`, `customer_support`
- **Session Security** - Session terenkripsi dengan driver database

---

## Tim Pengembang

| Nama | NIM | Peran |
|---|---|---|
| *Taura Mohamad Inzaghi* | *G6401231019* | Backend & Akses Keamanan (AAA) |
| *Muhammad Fauzan Akbar* | *G6401231045* | Integrator, QA (Pengujian), & Technical Writer |
| *Muhammad Syafiq Romadhon* | *G6401231079* | Kriptografer (Enkripsi & Digital Signature) |

> **Mata Kuliah:** KOM1315 - Keamanan Jaringan Komputer  
> **Institusi:** *Institute Pertanian Bogor*  
> **Semester:** Genap 2025/2026

---

## Lisensi

Proyek ini dikembangkan untuk keperluan akademis. Seluruh hak cipta dimiliki oleh tim Kelompok 6 KOM1315 Semester Genap 2025/2026.

---

<p align="center">Dibuat dengan sepenuh hati oleh <strong>Kelompok 6 KOM1315</strong></p>
