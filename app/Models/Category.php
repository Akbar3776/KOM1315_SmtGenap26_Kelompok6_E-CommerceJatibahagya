<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'parent_id',
    ];

    /**
     * Relasi ke kategori induk (jika ada)
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relasi ke kategori anak (jika ada)
     *
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relasi ke produk yang termasuk dalam kategori ini
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
