@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <!-- Sidebar -->
            <div class="col-md-3 mb-3">
                @include('components.sidebar')
            </div>

            <!-- Profile Section -->
            <div class="col-md-9 mb-3">
                <h5 class="mb-2">Alamat Pengguna</h5>
                <!-- Flash Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Address List Section -->
                <div class="mb-2">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Alamat Lengkap</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (auth()->user()->addresses->isEmpty())
                                <p class="text-muted">Kamu belum memiliki alamat. Silakan tambahkan alamat terlebih dahulu.
                                </p>
                                <a href="{{ route('address.create') }}" class="btn btn-outline-primary">Tambah Alamat</a>
                            @else
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
                            @endif
                        </tbody>
                    </table>

                    <a href="{{ route('address.create') }}" class="btn btn-primary w-100">Tambah Alamat</a>
                </div>
            </div>
        </div>
    </div>
@endsection
