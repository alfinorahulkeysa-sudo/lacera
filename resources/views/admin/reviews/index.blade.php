@extends('layouts.admin')

@section('content')

{{-- Flash Alert --}}
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
        <i class="fas fa-check"></i>
    </div>
    <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
</div>
@endif

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kelola Review</h2>
        <p class="text-sm text-gray-500 mt-1">Moderasi dan kelola ulasan produk dari pelanggan</p>
    </div>
</div>

{{-- Info Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Butuh Persetujuan</p>
            <h3 class="text-lg font-bold text-gray-800">{{ $pendingCount }} Ulasan</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Telah Ditayangkan</p>
            <h3 class="text-lg font-bold text-gray-800">{{ $approvedCount }} Ulasan</h3>
        </div>
    </div>
</div>

{{-- Review Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Komentar</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        @if($review->product)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-50 border border-gray-100 shrink-0">
                                @if($review->product->image)
                                    <img src="{{ Storage::url($review->product->image) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 line-clamp-1" style="max-width: 150px;">{{ $review->product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-mono">ID: #{{ $review->product->id }}</p>
                            </div>
                        </div>
                        @else
                        <span class="text-gray-400 text-xs italic">Produk telah dihapus</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $review->user->name ?? 'Customer' }}</p>
                            <p class="text-xs text-gray-400">{{ $review->user->email ?? '' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex text-yellow-400 text-xs gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-600 line-clamp-2" style="max-width: 250px;">{{ $review->comment }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $review->created_at->format('d M Y H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($review->is_approved)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Tampil
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded-full border border-yellow-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex gap-2">
                            @if($review->is_approved)
                            <form action="{{ route('admin.reviews.disapprove', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-yellow-50 text-yellow-600 border border-yellow-200 rounded-lg text-xs font-bold hover:bg-yellow-500 hover:text-white transition" title="Batalkan Persetujuan">
                                    <i class="fas fa-eye-slash"></i> Sembunyikan
                                </button>
                            </form>
                            @else
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-600 border border-green-200 rounded-lg text-xs font-bold hover:bg-green-500 hover:text-white transition" title="Setujui Review">
                                    <i class="fas fa-eye"></i> Tampilkan
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-500 hover:text-white transition" title="Hapus Review">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-star text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-semibold">Belum ada review</p>
                        <p class="text-gray-400 text-xs mt-1">Ulasan dari pelanggan akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

@endsection
