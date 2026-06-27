<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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