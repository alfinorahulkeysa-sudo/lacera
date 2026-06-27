<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_price',
        'shipping_cost',
        'courier',
        'status',
        'paid_at',
        'processing_at',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'proof_of_payment',
        'shipping_receipt',
        'nama_lengkap',
        'email',
        'provinsi',
        'kota',
        'detail_address',
        'payment_method',
        'payment_reference',
        'payment_url',
    ];

    protected $casts = [
        'total_price'    => 'integer',
        'shipping_cost'  => 'integer',
        'paid_at'        => 'datetime',
        'processing_at'  => 'datetime',
        'shipped_at'     => 'datetime',
        'delivered_at'   => 'datetime',
        'completed_at'   => 'datetime',
    ];

    // ───────────────────────────────────────────
    // RELASI
    // ───────────────────────────────────────────

    /** Pesanan milik satu user/customer */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Pesanan punya banyak item produk */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ───────────────────────────────────────────
    // SCOPE — filter hanya pesanan yang SUDAH DIBAYAR
    // ───────────────────────────────────────────

    /**
     * Gunakan: Order::paid()->get()
     * Sesuaikan nilai status dengan yang ada di database Anda.
     */
    public function scopePaid($query)
    {
        return $query->whereIn('status', [
            'paid',
            'processing',
            'shipped',
            'delivered',
            'completed',
        ]);
        // Atau jika Anda pakai kolom paid_at:
        // return $query->whereNotNull('paid_at');
    }

    // ───────────────────────────────────────────
    // HELPER
    // ───────────────────────────────────────────

    /** Format total ke Rupiah */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp' . number_format($this->total_price, 0, ',', '.');
    }
}
