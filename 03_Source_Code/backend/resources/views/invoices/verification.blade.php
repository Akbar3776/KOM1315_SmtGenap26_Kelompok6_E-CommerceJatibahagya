@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-qrcode"></i> Verifikasi Invoice</h4>
                </div>
                <div class="card-body">
                    @if($valid)
                        <div class="text-center mb-4">
                            <div class="badge bg-success fs-3 py-3 px-5">
                                <i class="fas fa-check-circle"></i> VALID
                            </div>
                            <p class="mt-3 text-success">Digital signature invoice terverifikasi valid.</p>
                        </div>

                        @if($invoiceSignature && $order)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="35%">Invoice Signature ID</th>
                                        <td>#{{ $invoiceSignature->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order ID</th>
                                        <td>{{ $invoiceSignature->order_id }}</td>
                                    </tr>
                                    @if($order)
                                    <tr>
                                        <th>Order Code</th>
                                        <td>{{ $order->order_code }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Signed By</th>
                                        <td>{{ $invoiceSignature->signedByAdmin?->name ?? 'System' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Signed At</th>
                                        <td>{{ $invoiceSignature->signed_at?->format('d M Y, H:i:s') }} WIB</td>
                                    </tr>
                                    <tr>
                                        <th>Algorithm</th>
                                        <td><code>{{ $invoiceSignature->algorithm }}</code></td>
                                    </tr>
                                    @if($invoiceSignature->verified_at)
                                    <tr>
                                        <th>Verified At</th>
                                        <td>{{ $invoiceSignature->verified_at->format('d M Y, H:i:s') }} WIB</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Hash (SHA-256)</th>
                                        <td><small class="text-break">{{ $invoiceSignature->hash_value }}</small></td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    @else
                        <div class="text-center mb-4">
                            <div class="badge bg-danger fs-3 py-3 px-5">
                                <i class="fas fa-times-circle"></i> INVALID
                            </div>
                            <p class="mt-3 text-danger">{{ $reason ?? 'Signature tidak valid.' }}</p>
                        </div>

                        <div class="alert alert-warning">
                            <h5><i class="fas fa-exclamation-triangle"></i> Perhatian</h5>
                            <p class="mb-0">Invoice ini tidak dapat diverifikasi. Kemungkinan penyebab:</p>
                            <ul class="mb-0 mt-2">
                                <li>Data order telah dimodifikasi</li>
                                <li>Signature tidak cocok dengan hash</li>
                                <li>Public key tidak valid</li>
                            </ul>
                        </div>

                        @if($invoiceSignature)
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <th width="35%">Signature ID</th>
                                        <td>#{{ $invoiceSignature->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Order ID</th>
                                        <td>{{ $invoiceSignature->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Algorithm</th>
                                        <td><code>{{ $invoiceSignature->algorithm }}</code></td>
                                    </tr>
                                    @if(isset($verificationResult['stored_hash']) && isset($verificationResult['current_hash']))
                                    <tr>
                                        <th>Stored Hash</th>
                                        <td><small class="text-break">{{ $verificationResult['stored_hash'] }}</small></td>
                                    </tr>
                                    <tr>
                                        <th>Current Hash</th>
                                        <td><small class="text-break">{{ $verificationResult['current_hash'] }}</small></td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        @endif
                    @endif

                    <hr>
                    <div class="text-center">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle"></i> 
                            Alur Verifikasi: QR Code -> Invoice Signature ID -> Ambil Data Order -> Hash Ulang -> Verifikasi RSA Signature
                        </p>
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection