<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSignature extends Model
{
    protected $fillable = [
        'order_id',
        'signature_id',
        'signature',
        'data_hash',
        'algorithm',
        'signed_at',
        'verified_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}