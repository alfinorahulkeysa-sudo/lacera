<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * Tampilkan semua ulasan pelanggan.
     */
    public function index()
    {
        $reviews = Review::with(['user', 'product'])->latest()->paginate(15);
        
        $pendingCount = Review::where('is_approved', false)->count();
        $approvedCount = Review::where('is_approved', true)->count();
        
        return view('admin.reviews.index', compact('reviews', 'pendingCount', 'approvedCount'));
    }

    /**
     * Setujui ulasan agar tampil di halaman produk.
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil disetujui dan ditayangkan!');
    }

    /**
     * Sembunyikan ulasan (batalkan persetujuan).
     */
    public function disapprove(Review $review)
    {
        $review->update(['is_approved' => false]);
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Persetujuan ulasan berhasil dibatalkan!');
    }

    /**
     * Hapus ulasan dari database.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus!');
    }
}
