@extends('layouts.app')

@section('content')
    {{-- SECTION: Carousell --}}
    <section class="mb-2">
        @include('landing.carousel')
    </section>

    {{-- SECTION: Best Category --}}
    <section class="container py-5" data-aos="fade-up" data-aos-duration="1000">
        @include('landing.best-category')
    </section>

    {{-- SECTION: Flash Sale --}}
    <section class="bg-primary">
        <div class="container py-5">
            <h1 class="mb-2 text-white fw-bolder" data-aos="fade-up" data-aos-duration="1000">Flash Sale</h1>
            <div class="row">
                <div class="col-12 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-sm btn-primary">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: Poster --}}
    <section class="my-3">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 text-center mb-2" data-aos="fade-right" data-aos-duration="1000">
                    <img class="img-fluid rounded-4 mb-2" alt="" src="{{ asset('images/banner-1.png') }}" />
                    <button class="btn btn-primary rounded-4">
                        Lihat Selengkapnya
                    </button>
                </div>
                <div class="col-12 col-md-6 text-center mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <img class="img-fluid rounded-4 mb-2" alt="" src="{{ asset('images/banner-2.png') }}" />
                    <button class="btn btn-primary rounded-4">
                        Lihat Selengkapnya
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: Banner New Member --}}
    <section class="my-3" data-aos="fade-up" data-aos-duration="1000">
        <img class="img-fluid" src="{{ asset('images/promo.png') }}" alt="Promosi" />
    </section>

    {{-- SECTION: Best of The Month --}}
    <section class="my-3" data-aos="fade-up" data-aos-duration="1000">
        <div class="bg-primary py-3">
            <h1 class="text-center text-white fw-bolder my-2">
                Best Item Of The Month
            </h1>
            <p class="text-center text-white mb-2">
                Temukan beragam produk yang sesuai dengan gaya dan kebutuhan Anda.
            </p>
        </div>
        <div class="container my-3">
            <div class="text-center my-2">
                <button class="btn btn-primary me-1">10 Kursi Terbaik</button>
                <button class="btn btn-primary me-1">10 Meja Makan Terbaik</button>
                <button class="btn btn-primary me-1">10 Lemari Baju Terbaik</button>
            </div>
            <div class="row">
                <div class="col-12 col-md-3 mb-2">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button" class="btn btn-sm btn-primary">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3 mb-2">
                    <div class="card shadow-sm">
                        <img src="{{ asset('images/category-office.png') }}" class="bd-placeholder-img card-img-top"
                            alt="OFFICE" />
                        <div class="card-body">
                            <h6 class="card-title mb-2" style="text-align: justify">
                                Meja Belajar Smart Minimalist Deck 22"
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-danger text-decoration-line-through d-flex mb-0">Rp
                                    2.500.000</span>
                                <span class="fw-normal text-black d-flex mb-0">Rp 1.750.000</span>
                                <small class="fw-light text-muted-flex"><span class="text-warning bi bi-star-fill"></span>
                                    4.9 | 250 Ulasan</small>
                            </p>
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: Feature --}}
    <section class="mb-2">
        <div class="container px-2 py-2" id="featured-3">
            <!-- <h2 class="pb-2 border-bottom text-center">Kenapa Belanja di Sini?</h2> -->
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                <!-- ASLI & 100% TERJAMIN -->
                <div class="feature col" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center fs-2 mb-3"
                        style="width: 60px; height: 60px">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <h5>Asli & 100% Terjamin</h5>
                    <p>
                        Belanja produk furniture dengan jaminan kualitas terbaik dari
                        brand terpercaya yang menggunakan material unggulan.
                    </p>
                </div>

                <!-- PROMO FURNITURE TIAP HARI -->
                <div class="feature col" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center fs-2 mb-3"
                        style="width: 60px; height: 60px">
                        <i class="bi bi-tags"></i>
                    </div>
                    <h5>Promo Furniture Setiap Hari</h5>
                    <p>
                        Temukan koleksi furniture favorit kamu dengan promo spesial setiap
                        hari, untuk melengkapi rumahmu.
                    </p>
                </div>

                <!-- REVIEW TERPERCAYA -->
                <div class="feature col" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="feature-icon bg-primary bg-gradient text-white rounded-circle d-inline-flex align-items-center justify-content-center fs-2 mb-3"
                        style="width: 60px; height: 60px">
                        <i class="bi bi-chat-quote"></i>
                    </div>
                    <h5>Review Terpercaya</h5>
                    <p>
                        Baca ribuan ulasan terpercaya sebelum berbelanja, dari komunitas
                        pecinta furniture yang terverifikasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: #HarmoniRuang --}}
    <section class="mb-2">
        <div class="bg-primary">
            <div class="container h-100" data-aos="fade-up" data-aos-duration="1000">
                <div class="row align-items-center h-100">
                    <div class="col-12 col-md-3 py-3 d-flex justify-content-center">
                        <h4 class="text-white fw-bold text-center">
                            #Harmoni<span class="text-danger">Ruang</span>
                        </h4>
                    </div>
                    <div class="col-12 col-md-6 py-3 d-flex align-items-center justify-content-center">
                        <p class="text-center text-white fs-6">
                            Jadikan ruangmu lebih hidup dengan furniture yang menghadirkan
                            keistimewaan di setiap sudut
                        </p>
                    </div>
                    <div class="col-12 col-md-3 py-3 d-flex justify-content-center">
                        <button class="btn btn-secondary w-100">Lihat Semua</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-2">
            <div class="row">
                <div class="col-6 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <h6 class="fw-bold">
                        <div class="my-2">
                            <img class="img-fluid rounded-3" alt=""
                                src="{{ asset('images/category-bedroom.png') }}" />
                        </div>
                        <div class="d-flex justify-content-start my-2 text-primary">
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                        </div>
                        <span class="text-primary">#Harmoni</span><span class="text-danger">Ruang</span>
                    </h6>
                    <h6 class="fw-bolder">Diciptakan Nona Tsabitha</h6>
                    <p style="text-align: justify">
                        Saya sudah sangat lama ingin menemukan meja putih yang bagus, dan
                        ternyata produknya dibuat dengan sangat kokoh dan sangat rapih
                        hingga detail terkecil.
                    </p>
                </div>
                <div class="col-6 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <h6 class="fw-bold">
                        <div class="my-2">
                            <img class="img-fluid rounded-3" alt=""
                                src="{{ asset('images/category-bedroom.png') }}" />
                        </div>
                        <div class="d-flex justify-content-start my-2 text-primary">
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                        </div>
                        <span class="text-primary">#Harmoni</span><span class="text-danger">Ruang</span>
                    </h6>
                    <h6 class="fw-bolder">Diciptakan Nona Meutia</h6>
                    <p style="text-align: justify">
                        Fitur meja ini yang dapat diatur ketinggiannya menjadi sebuah
                        keunggulan terbesar, sehingga bisa digunakan di berbagai ruangan,
                        termasuk di dapur dan di ruang terbatas.
                    </p>
                </div>
                <div class="col-6 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <h6 class="fw-bold">
                        <div class="my-2">
                            <img class="img-fluid rounded-3" alt=""
                                src="{{ asset('images/category-bedroom.png') }}" />
                        </div>
                        <div class="d-flex justify-content-start my-2 text-primary">
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                        </div>
                        <span class="text-primary">#Harmoni</span><span class="text-danger">Ruang</span>
                    </h6>
                    <h6 class="fw-bolder">Diciptakan Tuan Faiz</h6>
                    <p style="text-align: justify">
                        Kesan pertama saya setelah pemasangan adalah kombinasi desain yang
                        bersih dan sederhana serta warna hitam lebih cocok dengan
                        interiornya daripada yang saya bayangkan.
                    </p>
                </div>
                <div class="col-6 col-md-3 mb-2" data-aos="fade-left" data-aos-duration="1000">
                    <h6 class="fw-bold">
                        <div class="my-2">
                            <img class="img-fluid rounded-3" alt=""
                                src="{{ asset('images/category-bedroom.png') }}" />
                        </div>
                        <div class="d-flex justify-content-start my-2 text-primary">
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                            <span class="bi bi-star-fill me-1"></span>
                        </div>
                        <span class="text-primary">#Harmoni</span><span class="text-danger">Ruang</span>
                    </h6>
                    <h6 class="fw-bolder">Diciptakan Tuan Yasir</h6>
                    <p style="text-align: justify">
                        Sofa ini benar-benar luar biasa! Bantalan dudukannya terasa kokoh
                        namun tetap nyaman, memberikan rasa stabilitas sempurna bahkan
                        saat saya meletakkan piring di atasnya
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION: Best CTA --}}
    <section class="mb-2">
        <div class="container">
            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                <!-- Pengantaran -->
                <div class="col d-flex align-items-start" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="icon-square bg-light text-primary flex-shrink-0 me-3 p-3 rounded">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h4 class="text-capitalize">Pengantaran</h4>
                        <p>
                            Belanja lebih mudah dan praktis. Kami siap mengantar langsung ke
                            rumah Anda dengan aman dan cepat.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>

                <!-- Perakitan -->
                <div class="col d-flex align-items-start" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="icon-square bg-light text-primary flex-shrink-0 me-3 p-3 rounded">
                        <i class="bi bi-tools"></i>
                    </div>
                    <div>
                        <h4 class="text-capitalize">Perakitan</h4>
                        <p>
                            Jangan repot merakit sendiri. Kami menyediakan layanan perakitan
                            agar furniture Anda siap digunakan.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>

                <!-- Click and Collect -->
                <div class="col d-flex align-items-start" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="icon-square bg-light text-primary flex-shrink-0 me-3 p-3 rounded">
                        <i class="bi bi-bag-check"></i>
                    </div>
                    <div>
                        <h4 class="text-capitalize">Click and Collect</h4>
                        <p>
                            Pesan online dan ambil langsung di toko kami tanpa perlu
                            menunggu lama. Hemat waktu dan lebih efisien.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>

                <!-- Desain Interior -->
                <div class="col d-flex align-items-start" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="icon-square bg-light text-primary flex-shrink-0 me-3 p-3 rounded">
                        <i class="bi bi-house-heart"></i>
                    </div>
                    <div>
                        <h4 class="text-capitalize">Desain Interior</h4>
                        <p>
                            Wujudkan harmoni ruang impian Anda bersama kami. Konsultasikan
                            desain terbaik untuk hunian Anda.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>

                <!-- Pembayaran -->
                <div class="col d-flex align-items-start" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="icon-square bg-light text-primary flex-shrink-0 me-3 p-3 rounded">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div>
                        <h4 class="text-capitalize">Pembayaran</h4>
                        <p>
                            Nikmati kemudahan transaksi dengan berbagai metode pembayaran
                            yang aman dan nyaman untuk Anda.
                        </p>
                        <a href="#" class="btn btn-sm btn-primary">Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
