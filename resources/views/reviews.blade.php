@extends('layouts.app')

@section('title', 'Ulasan Pelanggan - Lacera Official Store')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="text-center mb-10">
            <span class="inline-block bg-pink-100 text-pink-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">⭐ Real Customer Feedback</span>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Ulasan Pelanggan Lacera</h1>
            <p class="text-gray-500 text-sm mt-2 max-w-xl mx-auto">Lihat pengalaman nyata para pelanggan yang telah mencoba produk kecantikan dan perawatan tubuh terbaik dari Lacera.</p>
            
            <div class="inline-flex items-center gap-3 bg-white px-6 py-3 rounded-2xl border border-gray-100 shadow-sm mt-6">
                <div class="flex text-amber-400 text-lg">
                    ⭐⭐⭐⭐⭐
                </div>
                <div class="text-left">
                    <span class="text-lg font-black text-gray-900 leading-none block">{{ number_format($averageRating, 1) }} / 5.0</span>
                    <span class="text-[11px] text-gray-400">Dari {{ $totalReviews }} Ulasan Pembeli</span>
                </div>
            </div>
        </div>

        {{-- Reviews Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @forelse($reviews as $review)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex text-amber-400 text-xs gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <span class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>

                    <p class="text-gray-700 text-sm leading-relaxed mb-4 italic">"{{ $review->comment }}"</p>
                </div>

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 font-bold flex items-center justify-center text-xs shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800 leading-none">{{ $review->user->name ?? 'Customer Lacera' }}</p>
                            <p class="text-[10px] text-emerald-600 font-medium mt-0.5"><i class="fas fa-check-circle mr-0.5"></i> Pembeli Terverifikasi</p>
                        </div>
                    </div>

                    @if($review->product)
                    <a href="{{ route('product.show', $review->product->id) }}" class="text-[11px] font-semibold text-pink-600 hover:underline flex items-center gap-1">
                        Produk <i class="fas fa-arrow-right text-[9px]"></i>
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-gray-100">
                <div class="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-4 text-pink-500 text-2xl">
                    <i class="far fa-star"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Belum Ada Ulasan Ditampilkan</h3>
                <p class="text-gray-500 text-sm mt-1">Jadilah yang pertama memberikan ulasan produk di Lacera!</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($reviews->hasPages())
        <div class="flex justify-center">
            {{ $reviews->links() }}
        </div>
        @endif

    </div>
</div>
@endsection
