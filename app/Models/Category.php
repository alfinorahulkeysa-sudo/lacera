<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Mengizinkan kolom-kolom ini untuk diisi data
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'banner',
        'description',
    ];

    /**
     * Relasi ke tabel Product (One-to-Many)
     * Satu kategori memiliki banyak produk di dalamnya
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}