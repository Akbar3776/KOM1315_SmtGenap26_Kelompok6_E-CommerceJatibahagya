@if($order->orderItems->isNotEmpty())
    <a href="{{ route('invoices.download', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Download Invoice PDF">
        <i class="fas fa-file-pdf"></i> Download Invoice
    </a>
@endif