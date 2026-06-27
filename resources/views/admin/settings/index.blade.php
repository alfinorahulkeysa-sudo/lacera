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
        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Toko</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola identitas toko, kontak, dan tarif pengiriman</p>
    </div>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left: Store Identity --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-sm border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-store text-lacera"></i> Identitas & Kontak Toko
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Nama Toko</label>
                        <input type="text" name="store_name" value="{{ $settings['store_name'] }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Email Toko</label>
                        <input type="email" name="store_email" value="{{ $settings['store_email'] }}" required
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Telepon Toko</label>
                    <input type="text" name="store_phone" value="{{ $settings['store_phone'] }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Alamat Fisik Toko</label>
                    <textarea name="store_address" rows="3" required
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">{{ $settings['store_address'] }}</textarea>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-sm border-b border-gray-100 pb-3 flex items-center gap-2">
                    <i class="fas fa-truck text-lacera"></i> Konfigurasi Ongkos Kirim
                </h3>
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Ongkir Flat (Flat Shipping Cost) - Rp</label>
                    <input type="number" name="flat_shipping_cost" value="{{ $settings['flat_shipping_cost'] }}" required min="0"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    <p class="text-[10px] text-gray-400 mt-1">Tarif flat pengiriman yang dipromosikan atau digunakan secara manual jika diaktifkan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Tarif Dasar Pengiriman (Base Cost) - Rp</label>
                        <input type="number" name="base_shipping_cost" value="{{ $settings['base_shipping_cost'] }}" required min="0"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Tarif per Kg Tambahan - Rp</label>
                        <input type="number" name="per_kg_shipping_cost" value="{{ $settings['per_kg_shipping_cost'] }}" required min="0"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Right: Information / Actions --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-sm">Simpan Pengaturan</h3>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Pastikan informasi yang Anda masukkan sudah benar sebelum disimpan. Perubahan pengaturan ini akan langsung berdampak pada halaman checkout, konfirmasi pembayaran, dan informasi kontak pelanggan.
                </p>
                
                <button type="submit" class="w-full bg-lacera hover:bg-rose-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-pink-200 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
            
            <div class="bg-lacera-light rounded-2xl p-6 text-center">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="fas fa-info-circle text-lacera text-lg"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Butuh Bantuan?</h4>
                <p class="text-[11px] text-gray-500 leading-relaxed">Hubungi admin IT atau tim pengembang jika Anda memerlukan konfigurasi lanjutan untuk API RajaOngkir / Kurir eksternal.</p>
            </div>
        </div>
        
    </div>
</form>

@endsection
