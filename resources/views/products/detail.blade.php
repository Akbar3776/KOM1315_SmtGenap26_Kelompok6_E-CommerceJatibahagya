@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        {{-- Tombol Back --}}
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>

        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-4" alt="{{ $product->name }}">
            </div>
            <div class="col-md-6">

                <div id="PD-Head">
                    <p class="fw-bold text-danger p-0 my-0">[[Ruang Tamu]]</p>
                    <h3 class="py-0 my-2">{{ $product->name }}</h3>
                    <div class="d-flex align-items-center py-0 my-0">
                        <div class="text-primary">
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-fill"></span>
                            <span class="bi bi-star-half"></span>
                            <span class="bi bi-star"></span>
                        </div>
                        <small class="ms-2 fw-bolder text-primary">(4.5 dari 5, 250 Ulasan)</small>
                    </div>
                    <hr />
                </div>

                <div id="PD-Price">
                    @php
                        $hargaAsli = 3500000; // Contoh: 3500000
                        $diskonPersen = 50; // Misal diskon 15%
                        $hargaDiskon = $hargaAsli - ($hargaAsli * $diskonPersen) / 100;
                    @endphp

                    <h4 class="text-muted text-decoration-line-through mb-1">
                        Rp {{ number_format($hargaAsli, 0, ',', '.') }}
                    </h4>
                    <h4 class="text-primary">
                        Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                        <small class="text-danger fw-bold">-{{ $diskonPersen }}%</small>
                    </h4>
                </div>

                <div id="PD-Shipping">
                    <div class="table-responsive mt-4">
                        <table class="table table-borderless">
                            <tbody>
                                <tr class="p-0 m-0">
                                    <td class="p-0 m-0 text-nowrap">Pengiriman Reguler</td>
                                    <td class="p-0 m-0">
                                        <ul>
                                            <li>Rp 70.000 (Gratis ongkir untuk pembelian diatas Rp 2.000.000)</li>
                                            <li>Dikirim oleh <span class="fw-bold">JatiBahagya</span> dengan estimasi waktu
                                                pengiriman rata-rata 3 hari.</li>
                                        </ul>
                                    </td>
                                </tr>
                                <tr class="p-0 m-0">
                                    <td class="p-0 m-0 text-nowrap">Pengambilan di Toko</td>
                                    <td class="p-0 m-0">
                                        <ul>
                                            <li>
                                                Gratis tanpa biaya pengiriman.
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="PD-Variant" class="row g-2">
                    <div class="col-md-6">
                        <label for="variant-ukuran" class="form-label">Pilih Ukuran</label>
                        <select id="variant-ukuran" class="form-select">
                            <option selected disabled>-- Pilih Ukuran --</option>
                            <option value="S">Kecil (S)</option>
                            <option value="M">Sedang (M)</option>
                            <option value="L">Besar (L)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="variant-warna" class="form-label">Pilih Warna</label>
                        <select id="variant-warna" class="form-select">
                            <option selected disabled>-- Pilih Warna --</option>
                            <option value="abu">Abu-abu</option>
                            <option value="biru">Biru</option>
                            <option value="coklat">Coklat</option>
                        </select>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button id="addToCartBtn" class="btn btn-primary w-50 py-2">Tambah ke Keranjang</button>
                        <button id="buyNowBtn" class="btn btn-outline-primary w-50 py-2">Beli Sekarang</button>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted d-block my-1">100% Guaranteed and Durable Products</small>
                        <div class="d-flex align-items-start my-1">
                            <i class="bi bi-gift text-primary me-2 mt-1"></i>
                            <small class="fw-bolder">Dapatkan bonus hingga 5.000 poin! Cek kehadiran setiap hari di bulan Mei dan kumpulkan poin sebanyak-banyaknya!</small>
                        </div>
                        <div class="d-flex align-items-start my-1">
                            <i class="bi bi-cash-coin text-primary me-2 mt-1"></i>
                            <small class="fw-bolder">Belanja pertama kali? Nikmati cashback 3% tanpa batas!</small>
                        </div>                                    
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div id="PD-Info">
                    <div class="border-bottom">
                        <nav>
                            <div class="d-flex justify-content-center">
                                <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-detail-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-detail" type="button" role="tab"
                                        aria-controls="nav-detail" aria-selected="true">Detail Produk</button>
                                    <button class="nav-link" id="nav-review-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-review" type="button" role="tab"
                                        aria-controls="nav-review" aria-selected="false">Ulasan (1)</button>
                                    <button class="nav-link" id="nav-question-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-question" type="button" role="tab"
                                        aria-controls="nav-question" aria-selected="false">Tanya Jawab (2)</button>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-detail" role="tabpanel"
                            aria-labelledby="nav-detail-tab" tabindex="0">
                            <div class="mt-2">
                                <p>
                                    {!! $product->description !!}</p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab"
                            tabindex="0">
                            <div class="mt-2">
                                {{-- Dummy Review --}}
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            {{-- Avatar kiri --}}
                                            <img src="https://www.gravatar.com/avatar/1234567890abcdef" alt="user-avatar"
                                                class="rounded-circle" width="100" height="100">

                                            {{-- Konten kanan --}}
                                            <div class="ms-3 flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    {{-- Rating bintang --}}
                                                    <div class="text-warning">
                                                        <span class="bi bi-star-fill"></span>
                                                        <span class="bi bi-star-fill"></span>
                                                        <span class="bi bi-star-fill"></span>
                                                        <span class="bi bi-star-half"></span>
                                                        <span class="bi bi-star"></span>
                                                    </div>

                                                    {{-- Author & tanggal --}}
                                                    <small class="text-muted">Oleh <strong>J*** D**</strong> — 10 Januari
                                                        2025</small>
                                                </div>

                                                {{-- Judul review --}}
                                                <h6 class="mb-1 mt-2">Judul Ulasan</h6>

                                                {{-- Isi review --}}
                                                <p class="mb-1">
                                                    Produk sangat bagus! Sesuai dengan deskripsi, kualitas oke dan harga
                                                    terjangkau. Sangat puas!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-question" role="tabpanel" aria-labelledby="nav-question-tab"
                            tabindex="0">
                            <div class="mt-2">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted">A*** S*******</span>
                                            <span class="badge bg-dark ms-2">Sudah Dijawab</span>
                                        </div>
                                        <small class="text-muted">01 Mei 2025</small>
                                    </div>
                                    <hr class="my-2">
                                    <p class="fw-bolder mb-1">Q: Apakah ukuran sofa ini muat untuk 3 orang?</p>
                                    <p class="mb-0">Saya ingin memastikan apakah cukup nyaman untuk diduduki 3 orang
                                        dewasa
                                        secara bersamaan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Section --}}
        <div class="mt-5">
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto text-white">Sukses</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Produk berhasil ditambahkan ke keranjang!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#addToCartBtn").click(function() {
                var productId = {{ $product->id }};
                var quantity = 1;

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        showToast("Produk berhasil ditambahkan ke keranjang!");
                    },
                    error: function(xhr) {
                        showToast("Gagal menambahkan ke keranjang!", true);
                        console.log(xhr.responseText);
                    }
                });
            });

            function showToast(message, isError = false) {
                var toast = $("#liveToast");
                toast.find(".toast-body").text(message);

                if (isError) {
                    toast.find(".toast-header strong").text("Error");
                    toast.find(".toast-header").addClass("bg-danger text-white");
                } else {
                    toast.find(".toast-header strong").text("Sukses");
                    toast.find(".toast-header").removeClass("bg-danger text-white");
                }

                var bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        });
    </script>
@endsection
