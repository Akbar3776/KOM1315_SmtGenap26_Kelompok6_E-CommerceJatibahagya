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
                            @forelse (auth()->user()->addresses as $address)
                                <tr>
                                    <td>
                                        {{ ucwords(
                                            strtolower(
                                                sprintf(
                                                    '%s, %s, %s, %s, %s, %d',
                                                    ucwords($address->full_address),
                                                    $address->village->name,
                                                    $address->district->name,
                                                    $address->regency->name,
                                                    $address->province->name,
                                                    $address->postal_code
                                                )
                                            )
                                        ) }}
                                    </td>                                    
                                    <td>
                                        <a href="{{ route('address.edit', $address->id) }}"
                                            class="btn btn-warning btn-sm">Ubah</a>
                                        <form action="{{ route('address.destroy', $address->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Belum ada alamat</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('address.create') }}" class="btn btn-primary w-100">Tambah Alamat</a>
                </div>
            </div>
        </div>
    </div>
@endsection
