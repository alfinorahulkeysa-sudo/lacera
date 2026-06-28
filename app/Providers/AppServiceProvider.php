<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;    // Ditambahkan untuk force HTTPS
use Illuminate\Support\Facades\View; // Ditambahkan untuk membagikan variabel global
use Illuminate\Support\Facades\Auth; // Ditambahkan untuk mengecek login user
use App\Models\CartItem;             // Ditambahkan untuk mengambil data keranjang

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS di production (Render, dll) agar semua URL yang
        // di-generate Laravel (form action, asset, redirect) menggunakan https://
        if (app()->environment('production') || str_contains((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Bagikan data jumlah item keranjang ke seluruh view/halaman secara otomatis
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Menghitung total quantity semua produk yang ada di keranjang user
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
                $view->with('cartCount', $cartCount);
            } else {
                // Jika pengunjung belum login, set jumlah keranjang menjadi 0
                $view->with('cartCount', 0);
            }
        });
    }
}