@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Checkout</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('cart.checkout.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    {{-- Informasi Pengiriman --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Alamat Pengiriman</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                    value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" required
                                    value="{{ old('phone', auth()->user()->phone) }}">
                            </div>

                            @foreach (auth()->user()->addresses as $address)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="address_id"
                                        id="address{{ $address->id }}" value="{{ $address->id }}">
                                    <label class="form-check-label" for="address{{ $address->id }}">
                                        {{ ucwords(
                                            strtolower(
                                                sprintf(
                                                    '%s, %s, %s, %s, %s, %d',
                                                    ucwords($address->full_address),
                                                    $address->village->name,
                                                    $address->district->name,
                                                    $address->regency->name,
                                                    $address->province->name,
                                                    $address->postal_code,
                                                ),
                                            ),
                                        ) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Metode Pembayaran</h5>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="COD" checked>
                                <label class="form-check-label" for="cod">Bayar di Tempat (COD)</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                    value="Transfer Bank">
                                <label class="form-check-label" for="transfer">Transfer Bank Virtual Account (VA)</label>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    {{-- Ringkasan Pembelian --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <h5 class="mb-3">Ringkasan Pembelian</h5>
                            <ul class="list-group mb-3">
                                @foreach ($cartItems as $cartItem)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $cartItem->product->name }} ({{ $cartItem->quantity }})</span>
                                        <span class="text-nowrap">Rp
                                            {{ number_format($cartItem->quantity * $cartItem->product->price, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Ongkos Kirim</span>
                                    <span>Rp 75.000</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>Biaya Layanan</span>
                                    <span>Rp 2.000</span>
                                </li>
                            </ul>

                            <h4 class="fw-bold text-end">
                                Total: <span class="text-danger">Rp
                                    {{ number_format($cartItems->sum(fn($item) => $item->quantity * $item->product->price), 0, ',', '.') }}</span>
                            </h4>
                        </div>
                    </div>

                    {{-- Tombol Checkout --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary w-100">Proses Pesanan</button>
                    </div>
                </div>
            </div>

        </form>
    </div>
@endsection
