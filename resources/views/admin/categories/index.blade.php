@extends('layouts.admin') 

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Kategori</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola semua kategori produk di toko</p>
    </div>
    <div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kategori
        </a>
    </div>
</div>

{{-- Alert Notifikasi Sukses --}}
@if(session('success'))
<div class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

{{-- Alert Notifikasi Gagal/Error --}}
@if(session('error'))
<div class="mb-4 p-4 text-sm text-red-800 bg-red-50 border border-red-200 rounded-xl flex items-center gap-2">
    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span class="font-medium">{{ session('error') }}</span>
</div>
@endif

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    {{-- Bagian Filter Pencarian dan Status --}}
    <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row gap-3">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-1 flex-col sm:flex-row gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-pink-500 focus:border-pink-500">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            
            <select name="status" class="border border-gray-300 rounded-lg text-sm px-4 py-2 focus:ring-pink-500 focus:border-pink-500 text-gray-700 bg-white">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Cari
            </button>

            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Reset
            </a>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-sm text-gray-600 font-semibold">
                    <th class="py-4 px-6 text-center w-16">No</th>
                    <th class="py-4 px-6">Kategori</th>
                    <th class="py-4 px-6">Deskripsi</th>
                    <th class="py-4 px-6 text-center">Jumlah Produk</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($categories as $index => $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-gray-700 text-center font-medium">{{ $categories->firstItem() + $index }}</td>
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center text-pink-600 border border-pink-100 flex-shrink-0">
                                {{-- Menampilkan ikon, jika kosong gunakan ikon default 'tags' --}}
                                <i class="{{ $category->icon ? (str_contains($category->icon, 'fa-') ? $category->icon : 'fas fa-' . $category->icon) : 'fas fa-tags' }}"></i> 
                            </div>
                            <span class="font-semibold text-gray-900">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 text-gray-600 max-w-xs truncate">
                        {{ $category->description ?? 'Tidak ada deskripsi' }}
                    </td>
                    <td class="py-4 px-6 text-center text-gray-700 font-medium">
                        <span class="bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded-md font-semibold">
                            {{ $category->products_count }} Produk
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                            Aktif
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        {{-- 💡 MODIFIKASI: Menghapus opacity-0 agar tombol aksi SELALU TERLIHAT jelas --}}
                        <div class="flex items-center justify-center gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="p-2 text-pink-600 bg-pink-50 border border-pink-200 rounded-lg hover:bg-pink-100 transition-colors" title="Edit Kategori">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>
                            
                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua produk di dalam kategori ini mungkin akan terpengaruh.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Kategori">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-500 font-medium bg-gray-50/30">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <i class="fas fa-folder-open text-gray-300 text-3xl"></i>
                            <span>Tidak ada data kategori yang ditemukan.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($categories->hasPages())
    <div class="p-4 border-t border-gray-200 bg-white">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection