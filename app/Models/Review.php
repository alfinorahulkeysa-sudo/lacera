<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    /**
     * Kolom-kolom yang diizinkan untuk diisi secara massal.
     * Disesuaikan dengan struktur tabel reviews di phpMyAdmin Anda.
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'is_approved'
    ];

    /**
     * Relasi balik ke User (Satu ulasan ditulis oleh satu user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi balik ke Product (Satu ulasan ditujukan untuk satu produk)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}