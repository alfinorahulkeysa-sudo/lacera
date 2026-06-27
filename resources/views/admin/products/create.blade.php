@extends('layouts.admin')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-lacera">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-lacera">Kelola Produk</a>
        <span>/</span>
        <span class="text-gray-800 font-semibold">Tambah Produk</span>
    </div>
    <h2 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h2>
</div>

@if ($errors->any())
    <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 border border-red-100 text-sm">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">Informasi Produk</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Glow & Scent Body Lotion" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#e81c62] focus:ring-1 focus:ring-[#e81c62] text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                        <textarea name="description" rows="5" placeholder="Tuliskan deskripsi dan manfaat produk..." 
                                  class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#e81c62] focus:ring-1 focus:ring-[#e81c62] text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">Harga & Stok</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" placeholder="0" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#e81c62] focus:ring-1 focus:ring-[#e81c62] text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" placeholder="0" 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#e81c62] focus:ring-1 focus:ring-[#e81c62] text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">Pengaturan Spesifik</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:border-[#e81c62] focus:ring-1 focus:ring-[#e81c62] text-sm bg-white">
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">Media</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:bg-gray-50 transition cursor-pointer relative">
                        
                        <div id="preview-container" class="hidden absolute inset-0 bg-white rounded-xl flex items-center justify-center p-2 z-10">
                            <img id="image-preview" src="#" alt="Preview" class="max-h-full max-w-full object-contain rounded-lg">
                            <button type="button" id="remove-preview" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-md text-xs transition flex items-center gap-1">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        </div>

                        <div class="space-y-1 text-center">
                            <i class="fas fa-image text-3xl text-gray-300 mb-2"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-[#e81c62] hover:text-rose-700">
                                    <span>Upload file</span>
                                    <input id="file-upload" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-[10px] text-gray-400">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-3 border-t border-gray-100 pt-5">
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
            Batal
        </a>
        <button type="submit" class="px-6 py-2.5 bg-[#e81c62] text-white rounded-xl font-medium hover:bg-rose-700 shadow-sm shadow-pink-200 transition text-sm flex items-center gap-2">
            <i class="fas fa-save"></i> Simpan Produk
        </button>
    </div>
</form>

<script>
    document.getElementById('file-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            
            // Eksekusi saat file gambar selesai dibaca oleh sistem browser
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden'); // Memunculkan container preview
            }
            
            reader.readAsDataURL(file);
        }
    });

    // Aksi tombol hapus pratinjau gambar jika admin berubah pikiran
    document.getElementById('remove-preview').addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Mencegah klik menyebar ke container luar
        document.getElementById('file-upload').value = ''; // Reset input file kembali kosong
        document.getElementById('preview-container').classList.add('hidden'); // Sembunyikan container preview
    });
</script>
@endsection