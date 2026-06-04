<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSignature extends Model
{
    protected $fillable = [
        'order_id',
        'signature',
        'hash_value',
        'algorithm',
        'signed_by_admin_id',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function signedByAdmin()
    {
        return $this->belongsTo(User::class, 'signed_by_admin_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order?->status) {
            'pending' => 'Menunggu Pembayaran',
            'process' => 'Sedang Diproses',
            'shipped' => 'Sedang Dikirim',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan',
            default => 'Unknown',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->order?->payment_status) {
            'unpaid' => 'Belum Bayar',
            'paid' => 'Sudah Bayar',
            'refunded' => 'Dikembalikan',
            default => 'Unknown',
        };
    }
}