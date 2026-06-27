@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Kelola Produk</h2>
        <p class="text-xs text-gray-500 mt-1">Kelola semua produk yang tersedia di toko.</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-sm">
            <i class="fas fa-file-export text-lacera"></i> Export
        </button>
        <a href="{{ route('admin.products.create') }}" class="bg-[#e81c62] hover:bg-rose-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition flex items-center gap-2 shadow-sm shadow-pink-200">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>
</div>

{{-- 🌟 PERBAIKAN 1: Alert untuk menampilkan pesan sukses dari Controller --}}
@if (session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 border border-green-100 text-sm flex items-center gap-2 shadow-sm animate-fade-in">
        <i class="fas fa-check-circle text-base"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
@endif

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    
    {{-- 🌟 PERBAIKAN SELESAI (LANGKAH 2): Mengubah area pembungkus filter menjadi Form GET --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="p-5 border-b border-gray-50 flex flex-wrap items-center gap-3 bg-gray-50/30">
        
        {{-- Input Pencarian --}}
        <div class="relative flex-1 min-w-[200px]">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white"
                   onchange="this.form.submit()">
        </div>
        
        {{-- Dropdown Kategori --}}
        <select name="category_id" onchange="this.form.submit()" class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white min-w-[160px]">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        
        {{-- Dropdown Status --}}
        <select name="status" onchange="this.form.submit()" class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white min-w-[130px]">
            <option value="">Status Aktif</option>
            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        
        {{-- Dropdown Stok --}}
        <select name="stock" onchange="this.form.submit()" class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none bg-white min-w-[130px]">
            <option value="">Stok</option>
            <option value="tersedia" {{ request('stock') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="habis" {{ request('stock') == 'habis' ? 'selected' : '' }}>Habis</option>
        </select>
        
        {{-- Tombol Reset (Diubah menjadi Link <a> agar membersihkan Query URL) --}}
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-2 transition">
            <i class="fas fa-sync-alt"></i> Reset
        </a>
        
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-100">
                    <th class="p-4 w-10 text-center"><input type="checkbox" class="rounded text-pink-600 focus:ring-pink-500"></th>
                    <th class="p-4 font-semibold">Produk</th>
                    <th class="p-4 font-semibold">Kategori</th>
                    <th class="p-4 font-semibold">Harga</th>
                    <th class="p-4 font-semibold text-center">Stok</th>
                    <th class="p-4 font-semibold text-center">Terjual</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 text-center"><input type="checkbox" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500"></td>
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg bg-gray-50 border border-gray-100 p-1 flex-shrink-0">
                                <img src="{{ asset('storage/' . $product->image) }}" onerror="this.src='https://via.placeholder.com/100'" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-md">
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-xs line-clamp-1">{{ $product->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">SKU: LC-P{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</p>
                                @if($loop->first || $loop->iteration == 2)
                                    <span class="inline-block mt-1 px-1.5 py-0.5 bg-green-50 text-green-600 text-[9px] font-bold rounded">Best Seller</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-xs text-gray-600">{{ $product->category->name ?? '-' }}</td>
                    <td class="p-4">
                        @php
                            $discount = $product->promo ? $product->promo->value : 0;
                            $discountedPrice = $product->price - ($product->price * ($discount / 100));
                        @endphp
                        <p class="font-bold text-gray-800 text-xs">Rp{{ number_format($discountedPrice, 0, ',', '.') }}</p>
                        @if($discount > 0)
                        <div class="flex items-center gap-1 mt-0.5">
                            <p class="text-[10px] text-gray-400 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                            <span class="bg-rose-50 text-rose-500 text-[9px] font-bold px-1 rounded">-{{ $discount }}%</span>
                        </div>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        {{-- 🌟 PERBAIKAN 2: Stok dinamis mengambil dari database, warna merah jika stok kosong --}}
                        <p class="font-bold text-xs {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $product->stock }}
                        </p>
                        <p class="text-[9px] text-gray-400">
                            {{ $product->stock > 0 ? 'Stok Tersedia' : 'Stok Habis' }}
                        </p>
                    </td>
                    <td class="p-4 text-center text-xs text-gray-600">{{ $product->sold_count ?? 0 }}</td>
                    <td class="p-4 text-center">
                        {{-- Menyesuaikan status berdasarkan stok --}}
                        @if($product->stock > 0)
                            <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-lg border border-emerald-100">Aktif</span>
                        @else
                            <span class="bg-gray-50 text-gray-400 text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-100">Nonaktif</span>
                        @endif
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="w-7 h-7 rounded-lg bg-gray-50 text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition flex items-center justify-center" title="Lihat">
                                <i class="far fa-eye text-xs"></i>
                            </button>
                            
                            {{-- Tombol Edit Anda (Sudah Sempurna) --}}
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="w-7 h-7 rounded-lg bg-rose-50 text-rose-500 hover:text-rose-700 hover:bg-rose-100 transition flex items-center justify-center border border-rose-100" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            
                            {{-- Form Tombol Hapus Data --}}
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 rounded-lg bg-red-50 text-red-500 hover:text-red-700 hover:bg-red-100 transition flex items-center justify-center border border-red-100" title="Hapus">
                                    <i class="far fa-trash-alt text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">Belum ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-50">
        {{ $products->links() }}
    </div>
</div>
@endsection