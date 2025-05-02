@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <h4 class="mb-2">Produk</h4>

        {{-- Form Filter dan Sorting --}}
        <form action="{{ route('products.all') }}" method="GET" class="mb-4">
            <div class="row">
                {{-- Filter Kategori --}}
                <div class="col-md-3 mb-2">
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Brand --}}
                <div class="col-md-3 mb-2">
                    <select name="brand" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Brand --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sorting --}}
                <div class="col-md-3 mb-2">
                    <select name="sort_by" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Urutkan --</option>
                        <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nama Barang (A-Z)
                        </option>
                        <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nama Barang
                            (Z-A)</option>
                        <option value="price_asc" {{ request('sort_by') == 'price_asc' ? 'selected' : '' }}>Harga Termurah
                        </option>
                        <option value="price_desc" {{ request('sort_by') == 'price_desc' ? 'selected' : '' }}>Harga
                            Termahal</option>
                    </select>
                </div>

                {{-- Search by Keyword --}}
                <div class="col-md-3 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari Produk..."
                        value="{{ request('search') }}">
                </div>

                {{-- Tombol Reset --}}
                <div class="col-md-3 mb-2">
                    <a href="{{ route('products.all') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        {{-- Keterangan Produk yang Ditampilkan --}}
        @if (request('category') || request('brand') || request('sort_by') || request('search'))
            <div class="alert alert-info mb-4">
                Menampilkan produk berdasarkan:
                @php $filters = [] @endphp

                @if (request('category'))
                    @php $filters[] = 'Kategori: ' . $categories->firstWhere('id', request('category'))->name @endphp
                @endif

                @if (request('brand'))
                    @php $filters[] = 'Brand: ' . $brands->firstWhere('id', request('brand'))->name @endphp
                @endif

                @if (request('sort_by'))
                    @php
                        $sortBy = '';
                        switch (request('sort_by')) {
                            case 'name_asc':
                                $sortBy = 'Nama Barang (A-Z)';
                                break;
                            case 'name_desc':
                                $sortBy = 'Nama Barang (Z-A)';
                                break;
                            case 'price_asc':
                                $sortBy = 'Harga Termurah';
                                break;
                            case 'price_desc':
                                $sortBy = 'Harga Termahal';
                                break;
                        }
                        if ($sortBy) {
                            $filters[] = 'Urutan: ' . $sortBy;
                        }
                    @endphp
                @endif

                @if (request('search'))
                    @php $filters[] = 'Cari: ' . request('search') @endphp
                @endif

                {{-- Menampilkan semua filter yang diterapkan --}}
                @if (count($filters) > 0)
                    <strong>{{ implode(' | ', $filters) }}</strong>
                @else
                    <strong>Semua produk</strong>
                @endif
            </div>
        @endif

        {{-- Daftar Produk --}}
        <div class="row">
            @forelse ($products as $product)
                <div class="col-12 col-md-3 mb-3" data-aos="fade-left" data-aos-duration="1000">
                    <div class="card shadow-sm h-100">
                        {{-- Tambahkan link ke halaman detail produk --}}
                        <a href="{{ route('products.detail', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" class="bd-placeholder-img card-img-top"
                                alt="{{ $product->name }}" />
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-2" style="">
                                <a href="{{ route('products.detail', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="card-text">
                                <span class="fw-normal text-black d-flex mb-0">
                                    @if ($product->variants->isNotEmpty())
                                        @php
                                            $minPrice = $product->variants->min('price');
                                            $maxPrice = $product->variants->max('price');
                                        @endphp

                                        @if ($minPrice == $maxPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @endif
                                    @else
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @endif
                                </span>
                                <small class="fw-light text-muted">
                                    <span class="text-warning bi bi-star-fill"></span> 4.9 | 250 Ulasan
                                </small>
                            </p>
                            <div class="mt-auto">
                                <button type="button" class="btn btn-sm btn-primary w-100" id="addToCartBtn"
                                    data-product-id="{{ $product->id }}">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-danger text-center">
                        Tidak ada produk yang ditemukan.
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>

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
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            // Delegate the click event for dynamically added buttons
            $(document).on('click', '#addToCartBtn', function() { // <-- Key change
                var productId = $(this).data('product-id'); // <-- Get product ID from data attribute
                var quantity = 1; // You can make this dynamic if needed

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
                        updateCartCount(response.cartCount); // Update cart count (if needed)
                    },
                    error: function(xhr) {
                        showToast("Gagal menambahkan ke keranjang!", true);
                        console.error("Error:", xhr
                            .responseText); // Use console.error for errors
                        if (xhr.status === 422) { // Check for validation errors
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                showToast(value, true); // Display each validation error
                            });
                        }
                    }
                });
            });

            function showToast(message, isError = false) {
                var toast = $("#liveToast"); // Make sure you have a toast element with this ID in your HTML
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

            function updateCartCount(cartCount) {
                // Select the cart count element and update its text
                $('.cart-count').text(cartCount); // Replace.cart-count with your actual class/selector
            }
        });
    </script>
@endsection
