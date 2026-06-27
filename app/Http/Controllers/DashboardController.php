<?php

namespace App\Http\Controllers;

use App\Models\Category; // Import model Category
use App\Models\Product;  // PENTING: Tambahkan import model Product di sini
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Mengambil semua data kategori (untuk navbar/menu Beranda bawaan kodemu)
        $categories = Category::all();
        
        // 2. Ambil produk PROMO (Hanya yang memiliki promo_id / tidak kosong)
        $produkPromo = Product::whereNotNull('promo_id')
                              ->latest()
                              ->take(4)
                              ->get();

        // 3. Ambil produk BEST SELLER (Diurutkan berdasarkan jumlah terjual tertinggi)
        $produkBestSeller = Product::orderBy('sold_count', 'desc')
                                   ->take(4)
                                   ->get();

        // 4. Ambil produk TERBARU (Diurutkan dari yang paling baru diinput)
        $produkTerbaru = Product::latest()
                                ->take(4)
                                ->get();

        // Kirim semua data ($categories dan semua jenis produk) ke file dashboard.blade.php
        return view('dashboard', compact('categories', 'produkPromo', 'produkBestSeller', 'produkTerbaru'));
    }
}