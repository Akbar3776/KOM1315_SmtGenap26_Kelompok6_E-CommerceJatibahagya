@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                @include('components.sidebar')
            </div>

            <!-- Main Content -->
            <div class="col-md-9 mb-3">
                <h5 class="mb-3"><i class="fas fa-shopping-bag"></i> Detail Pesanan</h5>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>Nomor Pesanan: #{{ $order->id }}</strong>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <!-- Status Pesanan -->
                        <h5 class="mt-4"><i class="fas fa-receipt"></i> Status Pesanan</h5>
                        <div class="mb-2 bg-light rounded">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th class="text-start">Tanggal</th>
                                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Status Pesanan</th>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'canceled' ? 'danger' : 'warning') }}">
                                                        <i class="fas fa-circle"></i>
                                                        {{ $order->status == 'completed' ? 'Selesai' : ($order->status == 'canceled' ? 'Dibatalkan' : 'Dalam Proses') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Status Pembayaran</th>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'refunded' ? 'secondary' : 'danger') }}">
                                                        <i class="fas fa-wallet"></i>
                                                        {{ $order->payment_status == 'paid' ? 'Lunas' : ($order->payment_status == 'refunded' ? 'Dikembalikan' : 'Belum Dibayar') }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pesanan</th>
                                                <td>Rp {{ number_format($order->total_order, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pengiriman</th>
                                                <td>Rp {{ number_format($order->total_shipping, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Biaya</th>
                                                <td>Rp {{ number_format($order->total_fee, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-start">Total Pembayaran</th>
                                                <td>
                                                    <b class="text-primary fw-bold">Rp
                                                        {{ number_format($order->amount, 0, ',', '.') }}
                                                    </b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <!-- Timeline Status Pesanan -->
                        <div class="mt-4">
                            <h6 class="fw-bold">Progres Pesanan</h6>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar 
                                @if ($order->status == 'pending') bg-warning 
                                @elseif($order->status == 'processed') bg-primary
                                @elseif($order->status == 'shipped') bg-info
                                @else bg-success @endif"
                                    role="progressbar"
                                    style="width: 
                                @if ($order->status == 'pending') 25%
                                @elseif($order->status == 'processed') 50%
                                @elseif($order->status == 'shipped') 75%
                                @else 100% @endif">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small>Pending</small>
                                <small>Diproses</small>
                                <small>Dikirim</small>
                                <small>Selesai</small>
                            </div>
                        </div>

                        <!-- Produk dalam Pesanan -->
                        <h5 class="mt-4"><i class="fas fa-box"></i> Produk dalam Pesanan</h5>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach ($order->orderItems as $item)
                                    <div class="card shadow-sm border-0 mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-3">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    class="img-fluid rounded-start" alt="Product Image">
                                            </div>
                                            <div class="col-md-9">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $item->product->name }}</h6>
                                                    <p class="card-text">
                                                        <strong>Jumlah:</strong> {{ $item->quantity }} <br>
                                                        <strong>Harga:</strong> Rp
                                                        {{ number_format($item->product->price, 0, ',', '.') }} <br>
                                                        <strong>Subtotal:</strong> Rp
                                                        {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Informasi Pengiriman -->
                        <h5 class="mt-4"><i class="fas fa-truck"></i> Informasi Pengiriman</h5>
                        <div class="mb-2">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="30%" class="text-start">Nama Penerima</th>
                                        <td>{{ $order->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Alamat</th>
                                        <td>{{ $order->shipping_address }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Kurir</th>
                                        <td>{{ $order->shipping_courier }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">No. Resi</th>
                                        <td>
                                            @if ($order->tracking_number)
                                                <span class="badge bg-info">{{ $order->tracking_number }}</span>
                                                <a href="https://cekresi.com/?noresi={{ $order->tracking_number }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-search"></i> Lacak Resi
                                                </a>
                                            @else
                                                <span class="text-muted">Belum tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="mt-4">
                            @if ($order->status == 'pending')
                                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-times"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif

                            @if ($order->status == 'shipped' && $order->tracking_number)
                                <a href="https://cekresi.com/?noresi={{ $order->tracking_number }}" target="_blank"
                                    class="btn btn-success">
                                    <i class="fas fa-map-marker-alt"></i> Lihat Resi
                                </a>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
