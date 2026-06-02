@extends('layouts.app')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <strong>Verifikasi Digital Signature</strong>
                        <span class="badge bg-primary">{{ $signature->algorithm }}</span>
                    </div>
                    <div class="card-body">
                        <form id="signatureVerifyForm" class="mb-4">
                            @csrf
                            <label for="signature_id" class="form-label">Signature ID</label>
                            <div class="input-group">
                                <input type="text" id="signature_id" name="signature_id" class="form-control"
                                    value="{{ $signature->signature_id }}" required>
                                <button type="submit" class="btn btn-primary">Verifikasi</button>
                            </div>
                        </form>

                        <div id="verificationResult" class="alert d-none"></div>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="text-start" width="35%">Signature ID</th>
                                    <td class="text-break">{{ $signature->signature_id }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Order</th>
                                    <td>{{ $signature->order?->order_code ?? '#' . $signature->order_id }}</td>
                                </tr>
                                @php
                                    $signedAmount = $signature->order_data['order']['amount']
                                        ?? $signature->order_data['amount']
                                        ?? 0;
                                @endphp
                                <tr>
                                    <th class="text-start">Total Pembayaran</th>
                                    <td>Rp {{ number_format((float) $signedAmount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Ditandatangani</th>
                                    <td>{{ $signature->signed_at?->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Terakhir Diverifikasi</th>
                                    <td>{{ $signature->verified_at?->format('d M Y, H:i') ?? 'Belum pernah' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-start">Hash Data</th>
                                    <td class="text-break"><code>{{ $signature->data_hash }}</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('signatureVerifyForm').addEventListener('submit', async function (event) {
            event.preventDefault();

            const result = document.getElementById('verificationResult');
            result.className = 'alert alert-info';
            result.textContent = 'Memverifikasi signature...';

            try {
                const response = await fetch('{{ route('signature.verify') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    },
                    body: JSON.stringify({
                        signature_id: document.getElementById('signature_id').value,
                    }),
                });

                const payload = await response.json();
                result.className = response.ok ? 'alert alert-success' : 'alert alert-danger';
                result.textContent = payload.message;
            } catch (error) {
                result.className = 'alert alert-danger';
                result.textContent = 'Gagal menghubungi server verifikasi.';
            }
        });
    </script>
@endsection
