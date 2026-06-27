<?php

// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',   // Nama disimpan saat checkout agar tidak hilang jika produk dihapus
        'price',
        'quantity',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
    ];

    // ───────────────────────────────────────────
    // RELASI
    // ───────────────────────────────────────────

    /** Item ini bagian dari satu Order */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Item ini merujuk ke satu Produk.
     * withDefault() → jika produk sudah dihapus, tidak error
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault([
            'name'  => $this->product_name ?? 'Produk tidak tersedia',
            'image' => null,
            'sku'   => '-',
        ]);
    }

    // ───────────────────────────────────────────
    // HELPER
    // ───────────────────────────────────────────

    /** Subtotal per item */
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }

    /** Format subtotal ke Rupiah */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }
}
