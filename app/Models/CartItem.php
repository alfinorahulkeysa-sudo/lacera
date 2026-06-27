<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk pengisian massal (Mass Assignment).
     * Ini memastikan Laravel mengizinkan perubahan pada quantity saat di-update dari Controller.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 
        'product_id', 
        'quantity'
    ];

    /**
     * Relasi ke Model Product.
     * Menghubungkan item keranjang dengan data produk terkait.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke Model User (Opsional tetapi sangat direkomendasikan).
     * Menghubungkan item keranjang dengan pengguna yang memilikinya.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}