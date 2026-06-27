<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'promo_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'sold_count', // 🌟 Ditambahkan agar kolom sold_count di database bisa di-update
        'weight',
        'image'
    ];

    /**
     * Relasi balik ke Kategori (Satu produk memiliki satu kategori)
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Promo (Satu produk bisa memiliki satu promo/opsional)
     */
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    /**
     * Relasi ke Reviews (Satu produk memiliki banyak ulasan dari pembeli)
     * 🌟 WAJIB ADA untuk mendukung fitur hitung rata-rata rating di CategoryController
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}