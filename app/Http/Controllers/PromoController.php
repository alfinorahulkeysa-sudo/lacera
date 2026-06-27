<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil produk yang memiliki promo (promo_id tidak kosong)
        $query = Product::whereNotNull('promo_id')->with('promo');

        // 2. Filter Berdasarkan Kategori/Tipe Promo (Sidebar Atas)
        if ($request->has('type') && $request->type != 'all') {
            $query->whereHas('promo', function ($q) use ($request) {
                $q->where('type', $request->type);
            });
        }

        // 3. 🌟 PERBAIKAN: Filter Berdasarkan Rentang Harga
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 4. 🌟 PERBAIKAN: Logika Sorting (Urutkan)
        if ($request->has('sort')) {
            // Antisipasi jika parameter sort double di URL (ambil yang paling terakhir)
            $sort = is_array($request->sort) ? end($request->sort) : $request->sort;

            if ($sort == 'Harga Terendah') {
                $query->orderBy('price', 'asc');
            } elseif ($sort == 'Terlaris') {
                // Jika Anda punya kolom total_sold, ganti ke 'total_sold'
                $query->orderBy('id', 'asc'); 
            } else { // Default: Terbaru
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 5. Batasi 12 produk per halaman
        $promoProducts = $query->paginate(12);

        // 6. Kirim data ke view promo.blade.php
        return view('promo', compact('promoProducts'));
    }
}