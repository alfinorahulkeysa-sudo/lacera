@extends('layouts.admin') 

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-pink-600 gap-1 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Kelola Kategori
    </a>
    <h1 class="text-2xl font-bold text-gray-900 mt-2">Tambah Kategori Baru</h1>
</div>

<div class="bg-white border border-gray-200 rounded-xl shadow-sm max-w-2xl overflow-hidden">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
        @csrf

        {{-- Input Nama Kategori --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Body Lotion, Body Wash" class="w-full px-4 py-2 border @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Icon Kategori --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Class Icon FontAwesome <span class="text-red-500">*</span></label>
            <input type="text" name="icon" value="{{ old('icon', 'box') }}" placeholder="Contoh: soap, spa, leaf, heart" class="w-full px-4 py-2 border @error('icon') border-red-300 focus:ring-red-500 focus:border-red-500 @else border-gray-300 focus:ring-pink-500 focus:border-pink-500 @enderror rounded-lg text-sm" required>
            <p class="text-gray-400 text-xs mt-1">Masukkan kata kunci icon saja (misal: dimasukkan <b>soap</b> untuk icon sabun).</p>
            @error('icon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Deskripsi --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kategori</label>
            <textarea name="description" rows="4" placeholder="Tuliskan deskripsi singkat mengenai kategori produk ini..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-pink-500 focus:border-pink-500">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Input Banner Gambar --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Banner Gambar Kategori (Opsional)</label>
            <input type="file" name="banner" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 border border-gray-300 rounded-lg p-1">
            <p class="text-gray-400 text-xs mt-1">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB).</p>
            @error('banner') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-sm font-medium shadow-sm transition-colors">Simpan Kategori</button>
        </div>
    </form>
</div>
@endsection