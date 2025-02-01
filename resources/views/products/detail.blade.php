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
                <h3 class="mb-3">{{ $product->name }}</h3>
                <p class="text-muted">
                    <span class="fw-bold">Brand:</span> {{ $product->brand->name ?? 'N/A' }} <br>
                    <span class="fw-bold">Kategori:</span> {{ $product->category->name ?? 'N/A' }}
                </p>
                <p><strong>Harga: </strong>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p><strong>Deskripsi: </strong>{{ $product->description }}</p>

                {{-- Rating Produk --}}
                <div class="d-flex align-items-center my-3">
                    <div class="text-warning">
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-half"></span>
                        <span class="bi bi-star"></span>
                    </div>
                    <small class="ms-2">(4.5 dari 5, 250 Ulasan)</small>
                </div>

                {{-- Tambahkan ke Keranjang --}}
                <div class="mt-3">
                    <button id="addToCartBtn" class="btn btn-primary w-100 py-2">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>

        {{-- Review Section --}}
        <div class="mt-5">
            <h5>Ulasan Pelanggan</h5>

            {{-- Dummy Reviews --}}
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex">
                        <img src="https://www.gravatar.com/avatar/1234567890abcdef" alt="user-avatar" class="rounded-circle"
                            width="40" height="40">
                        <div class="ms-3">
                            <h6 class="mb-1">Andi Pratama</h6>
                            <div class="text-warning">
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-half"></span>
                                <span class="bi bi-star"></span>
                            </div>
                            <p class="mb-1">Produk sangat bagus! Sesuai dengan deskripsi, kualitas oke dan harga
                                terjangkau. Sangat puas!</p>
                            <small class="text-muted">Tanggal Review: 10 Januari 2025</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex">
                        <img src="https://www.gravatar.com/avatar/abcdef1234567890" alt="user-avatar" class="rounded-circle"
                            width="40" height="40">
                        <div class="ms-3">
                            <h6 class="mb-1">Monica Salimwijaya</h6>
                            <div class="text-warning">
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-fill"></span>
                                <span class="bi bi-star-half"></span>
                            </div>
                            <p class="mb-1">Kualitas produk sangat memuaskan. Pengiriman cepat, tetapi kemasannya sedikit
                                kurang rapi.</p>
                            <small class="text-muted">Tanggal Review: 8 Januari 2025</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulir untuk Menulis Review --}}
            {{-- <h5 class="mt-4">Tulis Ulasan Anda</h5>
            <form action="#" method="POST">
                <div class="mb-3">
                    <label for="review" class="form-label">Tuliskan pengalaman Anda dengan produk ini</label>
                    <textarea id="review" name="review" class="form-control" rows="4" placeholder="Berikan ulasan singkat di sini..."></textarea>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <label for="rating" class="me-2">Rating:</label>
                    <div id="rating" class="text-warning">
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-fill"></span>
                        <span class="bi bi-star-half"></span>
                        <span class="bi bi-star"></span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Ulasan</button>
            </form> --}}

            {{-- Toast --}}
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
