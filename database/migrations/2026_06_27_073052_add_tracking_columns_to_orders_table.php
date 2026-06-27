<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk menampung data Alamat Pengiriman Manual dari Form Checkout
            $table->string('nama_lengkap')->nullable()->after('user_id');
            $table->string('email')->nullable()->after('nama_lengkap');
            $table->string('provinsi')->nullable()->after('email');
            $table->string('kota')->nullable()->after('provinsi');
            $table->text('detail_address')->nullable()->after('kota');

            // Kolom untuk tracking integrasi Payment Gateway PaymenKu
            $table->string('payment_method')->nullable()->after('status'); // Contoh: MANDIRI_VA, BCA_VA, QRIS
            $table->string('payment_reference')->nullable()->after('payment_method'); // ID Transaksi / Referensi dari PaymenKu
            $table->string('payment_url')->nullable()->after('payment_reference'); // URL Pembayaran jika menggunakan tipe redirect/QRIS
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kembali kolom jika melakukan rollback migration
            $table->dropColumn([
                'nama_lengkap',
                'email',
                'provinsi',
                'kota',
                'detail_address',
                'payment_method',
                'payment_reference',
                'payment_url'
            ]);
        });
    }
};