# **Implementasi Protokol Keamanan dan Kriptografi pada Sistem E-Commerce Jatibahagya**

## **_Implementation of Security Protocols and Cryptography in the Jatibahagya E-Commerce System_**

Taura Mohamad Inzaghi<sup>1</sup>, Muhammad Syafiq Romadhon<sup>1</sup>, Muhammad Fauzan Akbar<sup>1</sup>

<sup>1</sup>Program Studi Ilmu Komputer, Sekolah Sains Data, Matematika, dan Informatika, IPB University, Bogor 16680

## **Abstrak**

Perkembangan transformasi digital mendorong perusahaan furnitur Jatibahagya untuk beralih dari proses manual ke platform e-commerce berbasis web. Namun, digitalisasi ini memunculkan kerentanan keamanan informasi seperti serangan _brute-force_, _session hijacking_, dan _Cross-Site Scripting_ (XSS). Penelitian ini bertujuan mengimplementasikan protokol keamanan komprehensif pada Jatibahagya WebApp, meliputi autentikasi berlapis, otorisasi berbasis peran, dan enkripsi data sensitif. Metode pengembangan sistem mengadopsi _layered architecture_ menggunakan _framework_ Laravel 11. Pengamanan login diimplementasikan dengan algoritma _hashing_ Bcrypt dan verifikasi _One-Time Password_ (OTP). Kerahasiaan komunikasi pengguna dan administrator pada fitur obrolan dilindungi menggunakan algoritma enkripsi simetris _Advanced Encryption Standard_ (AES). Hasil pengujian kualitatif menunjukkan bahwa seluruh skenario kerentanan berhasil dimitigasi. Analisis kinerja kuantitatif mengindikasikan adanya tambahan waktu pemrosesan (_overhead_) akibat proses enkripsi dan dekripsi pesan dibandingkan dengan pengiriman _plaintext_. Meskipun demikian, _overhead_ tersebut masih dalam batas toleransi untuk aplikasi _real-time_, sehingga sistem tetap responsif sekaligus menjamin kerahasiaan dan integritas data.

**Kata Kunci:** AES, _e-commerce_, enkripsi, keamanan informasi, otorisasi.

## **_Abstract_**

_The development of digital transformation encourages the Jatibahagya furniture company to shift from manual processes to a web-based e-commerce platform. However, this digitalization raises information security vulnerabilities such as brute-force attacks, session hijacking, and Cross-Site Scripting (XSS). This study aims to implement comprehensive security protocols on the Jatibahagya WebApp, including multi-layered authentication, role-based authorization, and sensitive data encryption. The system development method adopts a layered architecture using the Laravel 11 framework. Login security is implemented using the Bcrypt hashing algorithm and One-Time Password (OTP) verification. The confidentiality of user and administrator communication in the chat feature is protected using the Advanced Encryption Standard (AES) symmetric encryption algorithm. Qualitative test results show that all vulnerability scenarios are successfully mitigated. Quantitative performance analysis indicates an additional processing time overhead due to the message encryption and decryption processes compared to plaintext transmission. Nevertheless, this overhead is still within the tolerance limits for real-time applications, so the system remains responsive while ensuring the confidentiality and integrity of user data._

**_Keywords:_** _AES, cryptography, e-commerce, encryption, information security._

## **Pendahuluan**

Perkembangan teknologi informasi mendorong banyak perusahaan melakukan transformasi digital guna meningkatkan efisiensi dan jangkauan pasar. Jatibahagya, sebuah entitas bisnis yang memproduksi furnitur kayu jati, saat ini masih sangat bergantung pada administrasi manual. Kondisi ini membatasi efisiensi operasional serta menghambat proses interaksi dengan pelanggan. Untuk memecahkan masalah ini, dikembangkanlah Jatibahagya WebApp, sebuah aplikasi _e-commerce_ terintegrasi yang memfasilitasi penayangan katalog, manajemen inventaris, dan komunikasi _chatting_ antara pelanggan dengan staf _Customer Support_.

Digitalisasi sistem transaksi membawa risiko keamanan informasi yang masif. Data kredensial pengguna dan privasi pesan merupakan aset kritis yang berpotensi diretas melalui eksploitasi seperti _SQL Injection_, _Brute-force_, dan penyadapan jaringan. Oleh karena itu, pengamanan tidak cukup hanya di tingkat aplikasi eksternal, melainkan memerlukan protokol kriptografi yang tertanam kuat pada basis data dan transmisi _real-time_. Penelitian ini berfokus pada implementasi arsitektur keamanan Authentication, Authorization, and Accounting (AAA), pemanfaatan sandi _hashing_ Bcrypt, integrasi OTP, dan penerapan AES untuk menjamin kerahasiaan pertukaran pesan, serta melakukan analisis komparasi performa (_overhead_) akibat proses kriptografi tersebut.

## **Metode**

Penelitian ini menggunakan pendekatan rekayasa perangkat lunak dengan metode perancangan _Layered Architecture_. Sistem dibangun di atas _framework_ Laravel 11 dengan struktur basis data relasional MySQL yang terdiri atas 18 tabel. Mekanisme evaluasi ancaman dilakukan menggunakan _Threat Modeling_ untuk mengidentifikasi celah kerentanan sebelum perancangan protokol.

Implementasi keamanan difokuskan pada tiga tahap utama. Pertama, pengamanan basis data di mana kata sandi pengguna dilindungi melalui algoritma Bcrypt (12 _rounds_) yang memastikan proteksi dari serangan _rainbow tables_. Kedua, manajemen akses berbasis peran (RBAC) menggunakan _tools_ Filament 3, yang membagi hak akses ke dalam klaster _super_admin_, _staff_, dan _customer_support_. Ketiga, komunikasi pesan melalui _WebSocket_ diamankan dengan proses sanitasi masukan guna menolak skrip XSS, yang kemudian dilanjutkan dengan enkripsi simetris AES sebelum masuk ke tahap penyimpanan log basis data.

## **Hasil dan Pembahasan**

### **Keandalan Protokol Keamanan**

Berdasarkan log pengujian komprehensif, seluruh fitur keamanan utama beroperasi sesuai desain. Registrasi dan pengaturan ulang sandi tervalidasi sukses melalui injeksi kode OTP berbasis waktu. Pengujian isolasi _middleware_ pada Filament juga mengonfirmasi bahwa pengguna dengan otorisasi _customer_ tidak dapat melakukan penetrasi paksa ke _dashboard_ administratif (Skenario ADM-LG-05).

### **Analisis Kuantitatif Kinerja Sistem (Overhead)**

Pengujian dilakukan pada lingkungan lokal sebagai berikut:

- **Device name:** MSI
- **Processor:** 12th Gen Intel(R) Core(TM) i5-12450H (2.00 GHz)
- **Installed RAM:** 16.0 GB (15.7 GB usable)
- **Graphics card:** NVIDIA GeForce RTX 2050 (4 GB), Intel(R) UHD Graphics (128 MB)
- **Storage:** 1.17 TB of 1.38 TB used
- **System type:** 64-bit operating system, x64-based processor
- **Pen and touch:** No pen or touch input is available for this display

**Tabel 1 Perbandingan waktu rata-rata proses autentikasi (Plaintext vs AES Encryption)**

| **Ukuran Data Kredensial / Skenario** | **Waktu Rata-rata Plaintext (ms)** | **Waktu Rata-rata Enkripsi AES (ms)** | **Persentase Overhead (%)** |
| ----------------------------------- | ---------------------------------- | ------------------------------------- | --------------------------- |
| Kueri 1 (Pendek: < 50 Karakter)     | 0.942 | 0.968 | 2.73 |
| Kueri 2 (Sedang: 50 - 200 Karakter) | 0.938 | 0.986 | 5.16 |
| Kueri 3 (Panjang: > 200 Karakter)   | 1.049 | 1.068 | 1.87 |

**Rumus perhitungan overhead:**

\[\text{Overhead (\%)} = \frac{T_{enkripsi} - T_{plaintext}}{T_{plaintext}} \times 100\%\]

**\[MASUKKAN PEMBAHASAN TABEL DI SINI: Contoh: "Berdasarkan Tabel 1, terlihat bahwa overhead yang ditimbulkan oleh AES dibandingkan plaintext menunjukkan adanya tambahan waktu pemrosesan. Namun, total latensi rata-rata yang dihasilkan masih berada di bawah batas toleransi sehingga kualitas komunikasi real-time tidak terganggu."\]**

## **Simpulan**

Implementasi protokol keamanan berlapis pada Jatibahagya WebApp, mulai dari verifikasi OTP, RBAC, hingga perlindungan kriptografi, terbukti efektif dalam memitigasi kerentanan umum seperti intrusi paksa dan eksploitasi sesi. Penggunaan AES pada transmisi obrolan pelanggan sukses menyamarkan log jaringan dengan penambahan _overhead_ latensi yang dapat ditoleransi. Sistem secara keseluruhan berhasil mendemonstrasikan keandalan yang memenuhi standar perlindungan aset untuk platform _e-commerce_.

## **Daftar Pustaka**

Pressman RS. 2014. _Software Engineering: A Practitioner's Approach Ed ke-8_. New York (US): McGraw Hill.

Stallings W. 2017. _Cryptography and Network Security: Principles and Practice Ed ke-7_. Boston (US): Pearson.

Inzaghi TM, Romadhon MS, Akbar MF. 2026. Laporan Progress Report Keamanan Informasi Kelompok 6. Bogor (ID): IPB University.