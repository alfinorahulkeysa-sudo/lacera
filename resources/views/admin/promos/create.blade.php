@extends('layouts.admin') 

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.promos.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-pink-600 gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Kelola Promo
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Tambah Promo Baru</h1>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm max-w-2xl overflow-hidden">
    <form action="{{ route('admin.promos.store') }}" method="POST" class="p-6 space-y-5">
        @csrf

        {{-- Input Nama Promo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Promo <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Promo Spesial Gajian, Cantik Akhir Tahun" class="w-full px-4 py-2 border @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- 💡 PERBAIKAN: Hubungkan ke Produk (misal: Paket Seserahan Lacera) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Terapkan pada Produk <span class="text-red-500">*</span></label>
            <select name="product_id" class="w-full px-4 py-2 border @error('product_id') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm bg-white" required>
                <option value="" disabled selected>-- Pilih Produk yang Mendapat Promo --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Rp {{ number_format($product->price, 0, ',', '.') }})
                    </option>
                @endforeach
            </select>
            @error('product_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Tipe Promo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Promo <span class="text-red-500">*</span></label>
            <input type="text" name="type" value="{{ old('type') }}" placeholder="Contoh: Persentase, Nominal, Flash Sale" class="w-full px-4 py-2 border @error('type') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
            @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Nilai Promo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Promo / Diskon <span class="text-red-500">*</span></label>
            <input type="number" name="value" value="{{ old('value') }}" placeholder="Contoh: 15 (untuk persen) atau 50000 (untuk nominal potongan)" class="w-full px-4 py-2 border @error('value') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
            @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Tanggal Mulai & Tanggal Selesai --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-2 border @error('start_date') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
                @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2 border @error('end_date') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
                @error('end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.promos.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-sm font-medium shadow-sm transition-colors">Simpan Promo</button>
        </div>
    </form>
</div>
@endsection