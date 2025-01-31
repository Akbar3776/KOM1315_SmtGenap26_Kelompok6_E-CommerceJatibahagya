@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Keranjang Belanja</h4>

        {{-- Menampilkan Keranjang Belanja --}}
        <div class="row">
            @foreach (range(1, 3) as $index)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="row d-flex align-items-center">
                                {{-- Gambar Produk --}}
                                <div class="col-6 col-md-3 px-2 py-2">
                                    <img src="{{ asset('images/category-office.png') }}" alt="Product Image"
                                        class="img-fluid rounded-4" style="height: 100px">
                                </div>
                                {{-- Deskripsi Produk --}}
                                <div class="col-6 col-md-5">
                                    <h5 class="card-title">Produk Dummy {{ $index }}</h5>
                                    <p class="card-text text-muted">Rp {{ number_format(100000, 0, ',', '.') }}</p>
                                </div>
                                {{-- Jumlah dan Total --}}
                                <div class="col-6 col-md-2">
                                    <div class="d-flex align-items-center mb-2">
                                        <button class="btn btn-sm btn-outline-secondary mr-2">-</button>
                                        <input type="text" value="1"
                                            class="form-control form-control-sm text-center" style="width: 50px;">
                                        <button class="btn btn-sm btn-outline-secondary ml-2">+</button>
                                    </div>
                                    <p class="font-weight-bold mb-0">Total: Rp {{ number_format(100000, 0, ',', '.') }}</p>
                                </div>
                                {{-- Aksi --}}
                                <div class="col-6 col-md-2 mt-2 text-right">
                                    <button class="btn btn-sm btn-danger">Hapus</button>
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
                <h4 class="fw-bolder">Total: <span class="text-danger">Rp {{ number_format(300000, 0, ',', '.') }}</span></h4>
            </div>
            <div class="col-12 col-md-6 text-end">
                <a href="#" class="btn btn-success">Checkout</a>
            </div>
        </div>


    </div>
@endsection
