<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class UserReviewController extends Controller
{
    /**
     * Halaman daftar semua ulasan publik dari pelanggan
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(12);

        $averageRating = Review::where('is_approved', true)->avg('rating') ?: 5.0;
        $totalReviews = Review::where('is_approved', true)->count();

        return view('reviews', compact('reviews', 'averageRating', 'totalReviews'));
    }

    /**
     * Simpan ulasan produk dari pelanggan
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);

        Review::create([
            'user_id'     => auth()->id(),
            'product_id'  => $product->id,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'is_approved' => true, // Otomatis disetujui agar langsung tampil
        ]);

        return redirect()->back()->with('review_success', 'Terima kasih! Ulasan Anda berhasil dikirim dan ditayangkan.');
    }
}
