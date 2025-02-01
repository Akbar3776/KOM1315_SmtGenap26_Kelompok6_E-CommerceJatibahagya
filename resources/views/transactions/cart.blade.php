@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Keranjang Belanja</h4>

        {{-- Menampilkan Keranjang Belanja --}}
        <div class="row">
            @foreach ($cartItems as $cartItem)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row d-flex align-items-center">
                                {{-- Gambar Produk --}}
                                <div class="col-6 col-md-3 px-2 py-2">
                                    <img src="{{ asset('storage/' . $cartItem->product->image) }}"
                                        alt="{{ $cartItem->product->name }}" class="img-fluid rounded-4"
                                        style="height: 100px">
                                </div>
                                {{-- Deskripsi Produk --}}
                                <div class="col-6 col-md-5">
                                    <h5 class="card-title">{{ $cartItem->product->name }}</h5>
                                    <p class="card-text text-muted">Rp
                                        {{ number_format($cartItem->product->price, 0, ',', '.') }}</p>
                                </div>
                                {{-- Jumlah dan Total --}}
                                <div class="col-6 col-md-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <button class="btn btn-sm btn-outline-secondary update-quantity"
                                            data-id="{{ $cartItem->id }}" data-type="decrease">-</button>
                                        <input type="text" value="{{ $cartItem->quantity }}"
                                            class="form-control form-control-sm text-center quantity-input"
                                            data-id="{{ $cartItem->id }}" style="width: 50px;">
                                        <button class="btn btn-sm btn-outline-secondary update-quantity"
                                            data-id="{{ $cartItem->id }}" data-type="increase">+</button>
                                    </div>
                                    <p class="font-weight-bold mb-0">
                                        Total: Rp
                                        {{ number_format($cartItem->quantity * $cartItem->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                                {{-- Aksi --}}
                                <div class="col-6 col-md-2 mt-2 text-right">
                                    <button class="btn btn-sm btn-danger remove-item"
                                        data-id="{{ $cartItem->id }}">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Total dan Checkout --}}
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <h4 class="fw-bolder">Total:
                    <span class="text-danger">Rp
                        {{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price), 0, ',', '.') }}
                    </span>
                </h4>
            </div>
            <div class="col-12 col-md-6 text-end">
                <a href="#" class="btn btn-success">Checkout</a>
            </div>
        </div>

        {{-- Toast Container --}}
        <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Fungsi untuk menampilkan notifikasi Bootstrap Toast
            function showToast(message, type = 'success') {
                const toast = `
                <div class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
                $("#toast-container").append(toast);
                const toastElement = new bootstrap.Toast($("#toast-container .toast").last()[0]);
                toastElement.show();
            }

            // Update Quantity
            $(".update-quantity").click(function() {
                let cartId = $(this).data("id");
                let type = $(this).data("type");
                let inputField = $(`.quantity-input[data-id="${cartId}"]`);
                let currentQuantity = parseInt(inputField.val());

                if (type === "increase") {
                    currentQuantity++;
                } else if (type === "decrease" && currentQuantity > 1) {
                    currentQuantity--;
                }

                $.ajax({
                    url: `/cart/update/${cartId}`,
                    type: "PUT",
                    data: {
                        _token: "{{ csrf_token() }}",
                        quantity: currentQuantity
                    },
                    success: function(response) {
                        inputField.val(currentQuantity);
                        showToast("Jumlah produk diperbarui!", "success");
                        location.reload(); // Reload untuk update total harga
                    },
                    error: function() {
                        showToast("Terjadi kesalahan!", "danger");
                    }
                });
            });

            // Delete Item
            $(".remove-item").click(function() {
                let cartId = $(this).data("id");

                $.ajax({
                    url: `/cart/remove/${cartId}`,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        showToast("Produk dihapus dari keranjang!", "success");
                        location.reload(); // Reload halaman untuk update tampilan
                    },
                    error: function() {
                        showToast("Terjadi kesalahan!", "danger");
                    }
                });
            });
        });
    </script>
@endsection
