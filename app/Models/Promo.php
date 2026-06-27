<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment)
     * Disesuaikan dengan kolom yang ada pada database asli Anda
     */
    protected $fillable = [
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
    ];

    /**
     * Relasi ke Product (Satu promo bisa diterapkan ke banyak produk)
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'promo_id');
    }
}