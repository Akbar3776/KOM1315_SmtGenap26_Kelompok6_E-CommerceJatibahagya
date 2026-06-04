<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $order->order_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; line-height: 1.5; color: #333; padding: 30px; }
        .invoice-container { max-width: 800px; margin: 0 auto; }
        .invoice-header { border-bottom: 3px solid #1a5f7a; padding-bottom: 20px; margin-bottom: 30px; }
        .store-name { font-size: 28px; font-weight: bold; color: #1a5f7a; margin-bottom: 5px; }
        .store-info { font-size: 10px; color: #666; line-height: 1.4; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #333; text-align: right; margin-top: -50px; }
        .invoice-number { font-size: 14px; color: #666; text-align: right; margin-top: 5px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #1a5f7a; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; font-weight: bold; color: #555; padding: 3px 0; vertical-align: top; }
        .info-value { display: table-cell; width: 70%; padding: 3px 0; vertical-align: top; }
        .info-value::before { content: ": "; }
        .two-columns { display: table; width: 100%; margin-bottom: 20px; }
        .col { display: table-cell; width: 48%; vertical-align: top; }
        .col:first-child { padding-right: 4%; }
        .col:last-child { padding-left: 4%; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background-color: #1a5f7a; color: white; padding: 10px 8px; text-align: left; font-weight: bold; font-size: 11px; }
        .items-table td { padding: 8px; border-bottom: 1px solid #eee; vertical-align: top; }
        .items-table tr:nth-child(even) td { background-color: #f9f9f9; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .totals-table { width: 350px; margin-left: auto; border-collapse: collapse; }
        .totals-table td { padding: 6px 10px; }
        .totals-table .label { text-align: left; color: #555; }
        .totals-table .value { text-align: right; font-weight: bold; }
        .totals-table tr:last-child td { border-top: 2px solid #1a5f7a; font-size: 16px; color: #1a5f7a; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #ffc107; color: #333; }
        .status-process { background: #17a2b8; color: white; }
        .status-shipped { background: #007bff; color: white; }
        .status-completed { background: #28a745; color: white; }
        .status-canceled { background: #dc3545; color: white; }
        .payment-unpaid { background: #ffc107; color: #333; }
        .payment-paid { background: #28a745; color: white; }
        .payment-refunded { background: #6c757d; color: white; }
        .signature-section { margin-top: 40px; padding-top: 20px; border-top: 1px dashed #ccc; }
        .signature-info { display: table; width: 100%; margin-bottom: 15px; }
        .signature-info-item { display: table-cell; width: 25%; padding: 5px; font-size: 10px; }
        .signature-info-item strong { color: #1a5f7a; display: block; font-size: 9px; margin-bottom: 2px; }
        .qr-section { text-align: center; margin-top: 20px; padding: 15px; background: #f5f5f5; border-radius: 5px; }
        .qr-section img { width: 150px; height: 150px; }
        .qr-label { font-size: 10px; color: #666; margin-top: 8px; }
        .qr-label strong { color: #333; }
        .invoice-footer { margin-top: 40px; padding-top: 15px; border-top: 1px solid #eee; text-align: center; font-size: 9px; color: #999; }
        .mt-10 { margin-top: 10px; }
        .verification-flow { font-size: 10px; color: #666; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="store-name">{{ $storeName }}</div>
            <div class="store-info">
                {{ $storeAddress }}<br>
                Telp: {{ $storePhone }} | Email: {{ $storeEmail }}
            </div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">
                No. Invoice: <strong>{{ $order->order_code }}</strong><br>
                Tanggal: {{ $order->created_at->format('d/m/Y H:i') }} WIB
            </div>
        </div>

        <div class="two-columns">
            <div class="col">
                <div class="section">
                    <div class="section-title">DATA CUSTOMER</div>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Nama</div>
                            <div class="info-value">{{ $order->user->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $order->user->email ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Telepon</div>
                            <div class="info-value">{{ $order->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="section">
                    <div class="section-title">ALAMAT PENGIRIMAN</div>
                    <div class="info-value" style="white-space: pre-line;">{{ $order->shipping_address }}</div>
                    @if($order->notes)
                    <div class="info-row mt-10">
                        <div class="info-label">Catatan</div>
                        <div class="info-value">{{ $order->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DAFTAR ITEM</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 35%;">Nama Produk</th>
                        <th style="width: 15%;">Variant</th>
                        <th style="width: 10%;" class="text-center">Qty</th>
                        <th style="width: 17%;" class="text-right">Harga Satuan</th>
                        <th style="width: 18%;" class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                        <td>{{ $item->variant ? ($item->variant->name ?? 'Variant') : '-' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price_per_item, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Tidak ada item</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section">
            <table class="totals-table">
                <tr>
                    <td class="label">Total Item ({{ $order->orderItems->sum('quantity') }} pcs)</td>
                    <td class="value">Rp {{ number_format($order->total_order, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Biaya Pengiriman</td>
                    <td class="value">Rp {{ number_format($order->total_shipping, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Biaya Admin / Fee</td>
                    <td class="value">Rp {{ number_format($order->total_fee, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label"><strong>TOTAL PEMBAYARAN</strong></td>
                    <td class="value"><strong>Rp {{ number_format($order->amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="two-columns">
            <div class="col">
                <div class="section">
                    <div class="section-title">STATUS ORDER</div>
                    @php
                        $statusClass = match($order->status) {
                            'pending' => 'status-pending',
                            'process' => 'status-process',
                            'shipped' => 'status-shipped',
                            'completed' => 'status-completed',
                            'canceled' => 'status-canceled',
                            default => ''
                        };
                        $statusLabel = match($order->status) {
                            'pending' => 'Menunggu Pembayaran',
                            'process' => 'Sedang Diproses',
                            'shipped' => 'Sedang Dikirim',
                            'completed' => 'Selesai',
                            'canceled' => 'Dibatalkan',
                            default => ucfirst($order->status)
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>
            </div>
            <div class="col">
                <div class="section">
                    <div class="section-title">STATUS PEMBAYARAN</div>
                    @php
                        $paymentClass = match($order->payment_status) {
                            'unpaid' => 'payment-unpaid',
                            'paid' => 'payment-paid',
                            'refunded' => 'payment-refunded',
                            default => ''
                        };
                        $paymentLabel = match($order->payment_status) {
                            'unpaid' => 'Belum Bayar',
                            'paid' => 'Sudah Bayar',
                            'refunded' => 'Dikembalikan',
                            default => ucfirst($order->payment_status)
                        };
                    @endphp
                    <span class="status-badge {{ $paymentClass }}">{{ $paymentLabel }}</span>
                </div>
            </div>
        </div>

        <div class="signature-section">
            <div class="section-title">VERIFIKASI DIGITAL SIGNATURE</div>
            <div class="signature-info">
                <div class="signature-info-item">
                    <strong>Signature ID</strong>
                    #{{ $invoiceSignature->id }}
                </div>
                <div class="signature-info-item">
                    <strong>Algorithm</strong>
                    {{ $invoiceSignature->algorithm }}
                </div>
                <div class="signature-info-item">
                    <strong>Ditandatangani</strong>
                    {{ $invoiceSignature->signed_at->format('d/m/Y H:i:s') }} WIB
                </div>
                <div class="signature-info-item">
                    <strong>Hash (SHA-256)</strong>
                    <small style="word-break: break-all;">{{ substr($invoiceSignature->hash_value, 0, 32) }}...</small>
                </div>
            </div>

            <div class="qr-section">
                <img src="{{ $qrImage }}" alt="QR Code">
                <div class="qr-label">
                    <strong>Pindai QR Code untuk verifikasi invoice</strong><br>
                    <small>Invoice Signature ID: {{ $invoiceSignature->id }}</small>
                    <div class="verification-flow">
                        Alur verifikasi: QR -> Invoice Signature ID -> Ambil Data Order -> Hash Ulang -> Verifikasi RSA Signature -> VALID/INVALID
                    </div>
                </div>
            </div>
        </div>

        <div class="invoice-footer">
            <p>Dokumen ini ditandatangani secara digital menggunakan {{ $invoiceSignature->algorithm }}.</p>
            <p>Invoice ini dicetak pada {{ now()->format('d/m/Y H:i:s') }} WIB</p>
            <p class="mt-10">{{ $storeName }} - E-Commerce Platform</p>
        </div>
    </div>
</body>
</html>