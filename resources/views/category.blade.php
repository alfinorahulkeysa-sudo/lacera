<x-app-layout>

    {{-- Breadcrumbs --}}
    <div class="bg-white py-4 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center space-x-2 text-xs md:text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-1 hover:text-gray-700 transition">
                    <span>🏠</span> Beranda
                </a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-500">Keranjang</span>
                <span class="text-gray-300">/</span>
                <span class="text-gray-800 font-semibold">Checkout</span>
            </nav>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-gray-50/50 min-h-screen pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-medium">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    {{-- KOLOM KIRI: Alamat Pengiriman & Metode Pembayaran --}}
                    <main class="lg:col-span-7 space-y-6">
                        
                        {{-- Box 1: Informasi Kontak & Alamat Manual --}}
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">
                                📍 ALAMAT PENGIRIMAN (MANUAL)
                            </h3>

                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap Penerima</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->name ?? '') }}" required
                                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#d62175] focus:ring-1 focus:ring-[#d62175]">
                                @error('nama_lengkap') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#d62175] focus:ring-1 focus:ring-[#d62175]">
                                @error('email') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>

                            {{-- Grid Provinsi & Kota (Sekarang diubah jadi Text Input Manual) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Provinsi Tujuan</label>
                                    <input type="text" name="provinsi" value="{{ old('provinsi') }}" placeholder="Contoh: Jawa Barat" required
                                        class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#d62175] focus:ring-1 focus:ring-[#d62175]">
                                    @error('provinsi') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Kota / Kabupaten</label>
                                    <input type="text" name="kota" value="{{ old('kota') }}" placeholder="Contoh: Bandung" required
                                        class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#d62175] focus:ring-1 focus:ring-[#d62175]">
                                    @error('kota') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Detail Alamat --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Detail Alamat Rumah (Jalan, Blok, No. Rumah)</label>
                                <textarea name="detail_address" rows="3" required placeholder="Tulis alamat lengkap Anda di sini..."
                                    class="w-full text-xs p-3 border border-gray-200 rounded-xl focus:outline-none focus:border-[#d62175] focus:ring-1 focus:ring-[#d62175]">{{ old('detail_address') }}</textarea>
                                @error('detail_address') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Box 2: Pilihan Channel Pembayaran PaymenKu --}}
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4">
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">
                                💳 METODE PEMBAYARAN (PAYMENKU)
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-semibold text-gray-700">
                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                    <input type="radio" name="payment_method" value="MANDIRI_VA" checked class="text-[#d62175] focus:ring-0">
                                    <div class="flex flex-col">
                                        <span>Mandiri Virtual Account</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Konfirmasi Otomatis</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                    <input type="radio" name="payment_method" value="BCA_VA" class="text-[#d62175] focus:ring-0">
                                    <div class="flex flex-col">
                                        <span>BCA Virtual Account</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Konfirmasi Otomatis</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl hover:bg-gray-50 cursor-pointer transition">
                                    <input type="radio" name="payment_method" value="QRIS" class="text-[#d62175] focus:ring-0">
                                    <div class="flex flex-col">
                                        <span>QRIS (E-Wallet)</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Gopay, OVO, Dana, LinkAja</span>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                    </main>

                    {{-- KOLOM KANAN: Ringkasan Belanja & Total Harga --}}
                    <aside class="lg:col-span-5">
                        <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm space-y-4 sticky top-6">
                            <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider border-b border-gray-100 pb-3">
                                🛒 RINGKASAN BELANJA
                            </h3>

                            @php
                                // Karena manual pengiriman, tentukan tarif flat ongkir (Misal: Rp 15.000)
                                $flatShippingCost = 15000;
                                $grandTotal = $totalHarga + $flatShippingCost;
                            @endphp

                            <div class="space-y-2.5 text-xs text-gray-600 font-medium">
                                <div class="flex justify-between">
                                    <span>Total Harga Produk</span>
                                    <span class="text-gray-900 font-bold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Berat ({{ $totalBerat }} gram)</span>
                                    <span class="text-gray-400 font-normal">Manual Flat Rate</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-100 pb-3">
                                    <span>Ongkos Kirim</span>
                                    <span class="text-emerald-600 font-bold">Rp{{ number_format($flatShippingCost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 text-sm text-gray-800 font-black">
                                    <span>Total Pembayaran</span>
                                    <span class="text-lg text-[#d62175]">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Input Tersembunyi (Hidden Inputs) untuk dikirim ke Controller --}}
                            <input type="hidden" name="shipping_cost" value="{{ $flatShippingCost }}">
                            <input type="hidden" name="total_harga" value="{{ $grandTotal }}">

                            {{-- Tombol Submit Form --}}
                            <button type="submit" class="w-full bg-[#d62175] hover:bg-[#b81760] text-white font-bold py-3.5 rounded-xl text-xs uppercase tracking-wider transition shadow-md flex items-center justify-center gap-2 mt-4">
                                🔒 Lanjut ke Pembayaran PaymenKu
                            </button>

                            <p class="text-[10px] text-gray-400 text-center leading-relaxed">
                                Dengan menekan tombol di atas, Anda akan dialihkan secara aman ke halaman gerbang pembayaran PaymenKu.
                            </p>
                        </div>
                    </aside>

                </div>
            </form>
        </div>
    </div>
</x-app-layout>