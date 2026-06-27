<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        // Ambil produk berdasarkan ID beserta relasi promo dan kategori
        $product = Product::with(['promo', 'category'])->findOrFail($id);

        // Ambil ulasan yang disetujui untuk produk ini
        $reviews = Review::with('user')
            ->where('product_id', $product->id)
            ->where('is_approved', true)
            ->latest()
            ->get();

        $avgRating = $reviews->avg('rating') ?: 5.0;
        $reviewCount = $reviews->count();

        // Ambil 4 produk terkait dengan kategori yang sama
        $relatedProducts = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->take(4)
                                    ->get();

        return view('product.detail', compact('product', 'reviews', 'avgRating', 'reviewCount', 'relatedProducts'));
    }
}