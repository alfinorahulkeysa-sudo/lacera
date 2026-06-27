<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController; // Import CheckoutController sesuai file Anda

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute API untuk aplikasi Anda. Semua rute
| di dalam file ini secara otomatis bebas dari proteksi CSRF token,
| sehingga sangat cocok digunakan untuk Webhook / Callback dari pihak ketiga.
|
*/

// Rute khusus untuk menerima laporan pembayaran (Callback) dari PaymenKu
Route::post('/payment-callback', [CheckoutController::class, 'handleCallback']);