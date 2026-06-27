<x-app-layout>

    <div class="py-8 bg-[#f9fafb] min-h-screen font-sans">

        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

            @if(session()->has('success'))

                <div class="mb-4 p-4 text-xs text-green-800 rounded-2xl bg-green-50 border border-green-100 flex items-center gap-2" role="alert">

                    <span>✅</span>

                    <div>

                        <span class="font-bold">Berhasil!</span> {{ session('success') }}

                    </div>

                </div>

            @endif



            <nav class="text-xs text-gray-500 mb-4 flex items-center gap-2">

                <a href="/" class="hover:text-pink-600">🏠 Beranda</a>

                <span>&rsaquo;</span>

                <span class="text-gray-800 font-medium">Bundle Package</span>

            </nav>



            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-5 mb-6">

                <div>

                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight uppercase">BUNDLE PACKAGE LACERA</h1>

                    <p class="mt-1 text-xs text-gray-500">Paket hemat perawatan kulit lengkap untuk hasil maksimal</p>

                </div>

                <div class="mt-4 sm:mt-0 flex items-center gap-2 text-xs">

                    <span class="text-gray-500">Urutkan:</span>

                    <select class="rounded-lg border-gray-200 bg-white py-1.5 pl-3 pr-8 text-xs font-medium text-gray-700 focus:border-pink-500 focus:ring-pink-500 outline-none">

                        <option>Terlaris</option>

                        <option>Terbaru</option>

                        <option>Harga Terendah</option>

                    </select>

                </div>

            </div>



            <div class="flex flex-col lg:flex-row gap-6">

                

                <div class="w-full lg:w-[280px] shrink-0 space-y-6">

                    

                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">

                        <h3 class="text-xs font-black text-gray-800 uppercase tracking-wider mb-4">Kategori Bundle</h3>

                        <div class="space-y-1.5">

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-bold bg-pink-50 text-pink-600 border border-pink-100">

                                <span>📦</span> Semua Bundle

                            </a>

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-50 transition">

                                <span>🧴</span> Perawatan Tubuh

                            </a>

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-50 transition">

                                <span>🧖‍♀️</span> Perawatan Wajah

                            </a>

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-50 transition">

                                <span>✨</span> Brightening Series

                            </a>

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-50 transition">

                                <span>💧</span> Collagen Series

                            </a>

                            <a href="#" class="flex items-center gap-3 text-xs p-2.5 rounded-xl font-medium text-gray-600 hover:bg-gray-50 transition">

                                <span>🛍️</span> Hemat Set

                            </a>

                        </div>

                    </div>



                    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-6">

                        <h3 class="text-xs font-black text-gray-800 uppercase tracking-wider">Filter</h3>

                        

                        <div>

                            <p class="text-xs font-bold text-gray-700 mb-3">Rentang Harga</p>

                            <input type="range" class="w-full accent-pink-600 h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer">

                            <div class="flex justify-between text-[10px] text-gray-400 mt-2 font-medium">

                                <span>Rp0</span>

                                <span>Rp500.000+</span>

                            </div>

                        </div>



                        <div>

                            <p class="text-xs font-bold text-gray-700 mb-3">Manfaat</p>

                            <div class="space-y-2.5">

                                @foreach(['Mencerahkan', 'Melembapkan', 'Anti Acne', 'Collagen Boost', 'Wangi Tahan Lama'] as $manfaat)

                                <label class="flex items-center gap-2 text-xs text-gray-600 cursor-pointer group">

                                    <input type="checkbox" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">

                                    <span class="group-hover:text-pink-600 transition">{{ $manfaat }}</span>

                                </label>

                                @endforeach

                            </div>

                        </div>

                    </div>



                </div>



                <div class="flex-1">

                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">

                        

                        @forelse ($bundles as $bundle)

                            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden relative group hover:shadow-lg transition-all duration-300 flex flex-col justify-between p-3.5">

                                

                                {{-- 💡 PERBAIKAN: Mengubah bundle.show menjadi product.show --}}
                                <a href="{{ route('product.show', $bundle->id) }}" class="block space-y-3 cursor-pointer">

                                    <div class="w-full aspect-[4/3] bg-pink-50 rounded-xl overflow-hidden relative">

                                        @php

                                            $labels = ['BEST SELLER', 'HEMAT', 'BEST DEAL', 'BUNDLE HEMAT'];

                                            $randomLabel = $labels[array_rand($labels)];

                                        @endphp

                                        <div class="absolute top-2 left-2 z-10 bg-pink-600 text-white text-[9px] font-black px-2 py-1 rounded-md tracking-wider uppercase shadow-sm">

                                            {{ $randomLabel }}

                                        </div>



                                        @if($bundle->image)

                                            <img src="{{ asset('storage/' . $bundle->image) }}" alt="{{ $bundle->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        @else

                                            <div class="w-full h-full flex items-center justify-center text-pink-300 text-xs font-medium">No Image</div>

                                        @endif

                                    </div>



                                    <div class="space-y-1.5">

                                        <h2 class="text-xs font-extrabold text-gray-800 line-clamp-1 group-hover:text-pink-600 transition">

                                            {{ $bundle->name }}

                                        </h2>

                                        

                                        <p class="text-[10px] text-gray-500 line-clamp-1">

                                            Sabun + Body Lotion + Lip Treatment

                                        </p>



                                        <div class="flex flex-wrap gap-1.5 pt-1">

                                            <span class="inline-flex items-center gap-0.5 border border-pink-200 text-pink-500 text-[9px] font-semibold px-1.5 py-0.5 rounded-md">

                                                <span>✨</span> Brightening

                                            </span>

                                            <span class="inline-flex items-center gap-0.5 border border-pink-200 text-pink-500 text-[9px] font-semibold px-1.5 py-0.5 rounded-md">

                                                <span>💧</span> Collagen

                                            </span>

                                        </div>



                                        <div class="pt-2 flex items-end gap-1.5">

                                            <div class="text-sm font-black text-pink-600 leading-none">

                                                Rp{{ number_format($bundle->price, 0, ',', '.') }}

                                            </div>

                                            <div class="text-[10px] text-gray-400 line-through leading-none mb-0.5">

                                                Rp{{ number_format($bundle->price * 1.45, 0, ',', '.') }}

                                            </div>

                                            <div class="bg-pink-100 text-pink-600 font-bold text-[9px] px-1 py-0.5 rounded ml-auto">

                                                45% OFF

                                            </div>

                                        </div>



                                        <div class="text-[9px] text-gray-400 font-medium">

                                            Terjual 3.6K

                                        </div>

                                    </div>

                                </a>



                                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center gap-2 relative z-20">

                                    <form action="{{ route('cart.store', $bundle->id) }}" method="POST" class="flex-1 m-0">

                                        @csrf

                                        <input type="hidden" name="quantity" value="1">

                                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white text-[10px] font-bold py-2 px-2 rounded-lg transition-colors text-center uppercase tracking-wider shadow-sm">

                                            + Keranjang

                                        </button>

                                    </form>

                                    <button class="border border-gray-200 text-gray-400 hover:text-pink-600 hover:border-pink-200 hover:bg-pink-50 p-2 rounded-lg transition-colors" title="Tambah Ke Wishlist">

                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />

                                        </svg>

                                    </button>

                                </div>



                            </div>

                        @empty

                            <div class="col-span-full bg-white p-12 text-center rounded-2xl border border-gray-100">

                                <span class="text-4xl block mb-3">🎁</span>

                                <h3 class="text-sm font-bold text-gray-800">Belum Ada Bundle</h3>

                                <p class="text-xs text-gray-500 mt-1">Paket hemat Lacera sedang dalam persiapan.</p>

                            </div>

                        @endforelse



                    </div>



                    @if($bundles->hasPages())

                    <div class="mt-8 border-t border-gray-100 pt-6">

                        {{ $bundles->links() }}

                    </div>

                    @endif



                </div>

            </div>



            <div class="mt-12 bg-pink-50/50 border border-pink-100 rounded-2xl p-6 grid grid-cols-2 md:grid-cols-4 gap-6">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-pink-600 shadow-sm text-lg">🛡️</div>

                    <div>

                        <h4 class="text-xs font-bold text-gray-800">100% Produk Original</h4>

                        <p class="text-[10px] text-gray-500">Garansi uang kembali</p>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-pink-600 shadow-sm text-lg">🚚</div>

                    <div>

                        <h4 class="text-xs font-bold text-gray-800">Gratis Ongkir</h4>

                        <p class="text-[10px] text-gray-500">Seluruh Indonesia</p>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-pink-600 shadow-sm text-lg">🔒</div>

                    <div>

                        <h4 class="text-xs font-bold text-gray-800">Pembayaran Aman</h4>

                        <p class="text-[10px] text-gray-500">Verifikasi sistem</p>

                    </div>

                </div>

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-pink-600 shadow-sm text-lg">🎧</div>

                    <div>

                        <h4 class="text-xs font-bold text-gray-800">Customer Service</h4>

                        <p class="text-[10px] text-gray-500">08.00 - 21.00 WIB</p>

                    </div>

                </div>

            </div>



        </div>

    </div>

</x-app-layout>