<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h1 class="text-white fw-bolder mx-2" data-aos="fade-up" data-aos-duration="1000">Flash Sale</h1>
            <!-- Timer Countdown -->
            <div id="flash-sale-timer" class="text-white fw-bold me-3"></div>
        </div>
        <div class="d-flex align-items-center">
            <!-- Button Lihat Semua -->
            <a href="#" class="btn btn-secondary rounded-4">Lihat Semua</a>
        </div>
    </div>

    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($product_flash_sale as $product)
                @php
                    // Generate diskon random antara 20% - 70%
                    $discount = rand(20, 70);
                    $discounted_price = $product->price - $product->price * ($discount / 100);
                @endphp

                <div class="swiper-slide h-100">
                    <div class="card shadow-sm h-100 d-flex flex-column">
                        <a href="{{ route('products.detail', $product->id) }}">
                            <div class="product-image">
                                <span class="discount-tag">{{ $discount }}%</span>
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" />
                            </div>
                        </a>
                        <div class="card-body d-flex flex-column flex-grow-1">
                            <h6 class="card-title product-title mb-2">
                                <a href="{{ route('products.detail', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="card-text d-block">
                                <span class="fw-bolder text-danger text-decoration-line-through">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <span class="fw-normal text-black d-block">
                                    Rp {{ number_format($discounted_price, 0, ',', '.') }}
                                </span>
                                <small class="fw-light text-muted d-block">
                                    <span class="text-warning bi bi-star-fill"></span> 4.9 | 250 Ulasan
                                </small>
                            </p>
                            <div class="d-flex justify-content-center mt-auto">
                                <button type="button" class="btn btn-sm btn-primary">Tambah ke Keranjang</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Navigasi -->
        <div class="swiper-button-next bg-primary text-white px-3 py-3 mx-2 my-2 rounded-4"></div>
        <div class="swiper-button-prev bg-primary text-white px-3 py-3 mx-2 my-2 rounded-4"></div>
        <div class="swiper-pagination"></div>
    </div>
</div>
