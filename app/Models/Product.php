<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $stock
 * @property int $category_id
 * @property int $brand_id
 * @property string $image
 * @property string $status
 */
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount',
        'is_new_product',
        'stock',
        'category_id',
        'brand_id',
        'image',
        'status',
    ];

    protected $casts = [
        'is_new_product' => 'boolean',
        'discount' => 'float',
    ];

    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = $value;
        } elseif (!$this->exists) {
            $this->attributes['image'] = 'images/dummy/dummy-' . rand(1, 5) . '.png';
        }
    }

    public function getFinalPriceAttribute()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}
