<?php

namespace App\Models;

use App\Services\DigitalSignatureService;
use Illuminate\Database\Eloquent\Model;

class OrderSignature extends Model
{
    protected $fillable = [
        'order_id',
        'signature_id',
        'signature',
        'data_hash',
        'order_data',
        'algorithm',
        'signed_at',
        'verified_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'order_data' => 'array',
        'signed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getOrderDataAttribute(): array
    {
        $storedOrderData = $this->attributes['order_data'] ?? null;

        if ($storedOrderData) {
            $decodedOrderData = json_decode($storedOrderData, true);

            if (is_array($decodedOrderData)) {
                return $decodedOrderData;
            }
        }

        if ($this->order) {
            return app(DigitalSignatureService::class)->makeOrderDataFromOrder(
                $this->order,
                $this->signed_at?->toIso8601String()
            );
        }

        return [];
    }
}
