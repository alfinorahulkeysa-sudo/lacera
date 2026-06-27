<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class BestSellerController extends Controller
{
    public function index(Request $request)
    {
        // Inisialisasi query dasar: ambil produk dan hitung rata-rata ratingnya
        $productsQuery = Product::withAvg('reviews', 'rating');
        
        // 🌟 Logika Pencarian berdasarkan parameter ?search=... di URL
        $productsQuery->when($request->filled('search'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        
        // 🌟 Logika Filter Harga Minimum (?min_price=...)
        $productsQuery->when($request->filled('min_price'), function ($query) use ($request) {
            return $query->where('price', '>=', $request->min_price);
        });

        // 🌟 Logika Filter Harga Maksimum (?max_price=...)
        $productsQuery->when($request->filled('max_price'), function ($query) use ($request) {
            return $query->where('price', '<=', $request->max_price);
        });

        // 🌟 Logika Filter Rating Minimal (?rating=...)
        $productsQuery->when($request->filled('rating'), function ($query) use ($request) {
            return $query->where(function ($subQuery) use ($request) {
                $subQuery->selectRaw('AVG(rating)')
                         ->from('reviews')
                         ->whereColumn('reviews.product_id', 'products.id');
            }, '>=', $request->rating);
        });

        // 🌟 URUTAN UTAMA: Karena ini halaman Best Seller, selalu urutkan dari penjualan terbanyak
        $productsQuery->orderBy('sold_count', 'desc');

        // Mengambil data produk dengan paginasi 12 item per halaman
        $products = $productsQuery->paginate(12);
        
        // Mengambil semua kategori untuk tetap ditampilkan di sidebar kiri
        $categories = Category::all();

        // Mengarahkan ke file view resources/views/best-seller.blade.php
        return view('best-seller', compact('products', 'categories'));
    }
}