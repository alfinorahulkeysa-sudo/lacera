<x-app-layout>
<div class="bg-gray-50 min-h-screen py-6 text-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <nav class="flex items-center gap-1.5 text-xs text-gray-500 mb-2">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V11a2 2 0 01-2 2H5a2 2 0 01-2-2V9m16 4h-4m4 0a2 2 0 012 2v3a2 2 0 01-2 2h-3M1 10V3a1 1 0 011-1h2a1 1 0 011 1v7m14 0V3a1 1 0 011-1h2a1 1 0 011 1v7"></path>
            </svg>
            <a href="/" class="hover:underline">Beranda</a> 
            <span class="text-gray-400">&gt;</span> 
            <span class="text-gray-600 font-medium">Promo</span>
        </nav>
        
        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">KATALOG PROMO</h1>
        <p class="text-xs text-gray-500 mb-6">Temukan berbagai promo menarik dari Lacera Official Store</p>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            
            <div class="lg:col-span-1 space-y-4">
                
                <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-900 tracking-wider uppercase mb-3">Kategori Promo</h3>
                    <div class="space-y-1">
                        <a href="{{ route('promo.index', ['type' => 'all']) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ !request('type') || request('type') == 'all' ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Semua Promo</span>
                        </a>
                        @foreach(['Flash Sale', 'Diskon', 'Bundle Hemat', 'Buy 2 Get 1', 'Gratis Ongkir'] as $cat)
                        <a href="{{ route('promo.index', ['type' => $cat]) }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs {{ request('type') == $cat ? 'bg-pink-50 text-pink-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} transition">
                            <span>{{ $cat }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <form id="filter-form" action="{{ route('promo.index') }}" method="GET" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm space-y-4">
                    @if(request('type'))
                        <input type="hidden" name="type" value="{{ request('type') }}">
                    @endif

                    <h3 class="text-xs font-bold text-gray-900 tracking-wider uppercase border-b border-gray-50 pb-2">FILTER</h3>
                    
                    <div class="space-y-2">
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Tipe Promo</h4>
                        <div class="space-y-1.5">
                            @foreach(['Flash Sale', 'Diskon', 'Bundle Hemat', 'Buy 2 Get 1', 'Gratis Ongkir'] as $type)
                            <label class="flex items-center gap-2.5 text-xs text-gray-600 cursor-pointer select-none hover:text-gray-900">
                                <input type="checkbox" name="types[]" value="{{ $type }}" 
                                       {{ is_array(request('types')) && in_array($type, request('types')) ? 'checked' : '' }}
                                       onchange="this.form.submit()"
                                       class="rounded border-gray-300 text-pink-600 focus:ring-pink-500 w-3.5 h-3.5">
                                <span>{{ $type }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Rentang Harga</h4>
                            <span class="text-[10px] font-bold text-pink-600" id="price-display">Maks: Rp{{ number_format(request('max_price', 500000), 0, ',', '.') }}</span>
                        </div>
                        <input type="range" name="max_price" min="0" max="500000" step="10000"
                               value="{{ request('max_price', 500000) }}" 
                               onchange="this.form.submit()"
                               oninput="document.getElementById('price-display').innerText = 'Maks: Rp' + Number(this.value).toLocaleString('id-ID')"
                               class="w-full accent-pink-600 h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-[11px] text-gray-400 font-medium">
                            <span>Rp0</span>
                            <span>Rp500.000+</span>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Urutkan</h4>
                        <select id="sidebar-sort" name="sort" onchange="this.form.submit()" class="w-full text-xs border-gray-200 rounded-lg focus:ring-pink-500 focus:border-pink-500 bg-white py-1.5 pl-3 pr-10 text-gray-600">
                            <option value="Terbaru" {{ request('sort') == 'Terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="Terlaris" {{ request('sort') == 'Terlaris' ? 'selected' : '' }}>Terlaris</option>
                            <option value="Harga Terendah" {{ request('sort') == 'Harga Terendah' ? 'selected' : '' }}>Harga Terendah</option>
                        </select>
                    </div>
                    
                    @if(request('type') || request('types') || request('max_price') || request('sort'))
                        <a href="{{ route('promo.index') }}" class="block text-center text-[10px] font-bold text-red-500 hover:underline pt-2">
                            ❌ Hapus Semua Filter
                        </a>
                    @endif
                </form>
            </div>

            <div class="lg:col-span-3">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xs text-gray-500">Menampilkan <span class="font-bold text-gray-800">{{ $promoProducts->count() }}</span> produk promo</span>
                    
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span>Urutkan:</span>
                        <select onchange="document.getElementById('sidebar-sort').value = this.value; document.getElementById('filter-form').submit();" class="border-gray-200 rounded-lg focus:ring-pink-500 focus:border-pink-500 bg-white py-1.5 pl-3 pr-10 text-xs font-medium text-gray-700">
                            <option value="Terbaru" {{ request('sort') == 'Terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="Terlaris" {{ request('sort') == 'Terlaris' ? 'selected' : '' }}>Terlaris</option>
                            <option value="Harga Terendah" {{ request('sort') == 'Harga Terendah' ? 'selected' : '' }}>Harga Terendah</option>
                        </select>
                    </div>
                </div>

                @if($promoProducts->isEmpty())
                    <div class="bg-white rounded-xl p-12 text-center border border-gray-100 shadow-sm">
                        <p class="text-gray-400 text-sm">Belum ada produk promo aktif saat ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($promoProducts as $product)
                            @php
                                $discountPercent = $product->promo ? $product->promo->value : 0;
                                $discountedPrice = $product->price - ($product->price * ($discountPercent / 100));
                            @endphp
                            
                            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition flex flex-col justify-between relative group">
                                
                                <a href="{{ route('product.show', $product->id) }}" class="block relative flex-grow cursor-pointer">
                                    
                                    @if($product->promo)
                                        <span class="absolute top-2 left-2 bg-gradient-to-r from-pink-600 to-red-500 text-white text-[9px] font-extrabold px-2 py-0.5 rounded shadow-sm uppercase tracking-wider z-10">
                                            ⚡ {{ $product->promo->type }}
                                        </span>
                                    @endif

                                    <div>
                                        <div class="aspect-square bg-gray-50 relative overflow-hidden">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300' }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>

                                        <div class="p-3 space-y-1">
                                            <h2 class="font-bold text-gray-800 text-xs line-clamp-1 group-hover:text-pink-600 transition">
                                                {{ $product->name }}
                                            </h2>
                                            
                                            <div class="flex items-center gap-1.5 flex-wrap pt-0.5">
                                                <span class="text-sm font-extrabold text-pink-600">Rp{{ number_format($discountedPrice, 0, ',', '.') }}</span>
                                                @if($discountPercent > 0)
                                                    <span class="bg-pink-600 text-white font-extrabold text-[9px] px-1.5 py-0.5 rounded shadow-sm">
                                                        {{ $discountPercent }}% OFF
                                                    </span>
                                                @endif
                                            </div>

                                            @if($discountPercent > 0)
                                                <div class="text-[10px] text-gray-400 line-through leading-none">
                                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>

                                <div class="mt-auto flex flex-col w-full border-t border-gray-50">
                                    @if($product->promo && $product->promo->end_date)
                                        <div class="flex justify-between items-center text-[10px] px-3 pb-2.5 pt-2 mt-1">
                                            <div class="flex items-center gap-1 font-bold text-gray-700" 
                                                 id="countdown-{{ $product->id }}" 
                                                 data-endtime="{{ $product->promo->end_date }}">
                                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="timer-text">--:--:--</span>
                                            </div>
                                            <span class="text-gray-400 font-medium">Terjual {{ $product->sold_count ?? 0 }}</span>
                                        </div>
                                    @endif

                                    <form action="{{ route('cart.store', $product->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold text-xs py-2.5 transition tracking-wider uppercase rounded-b-xl">
                                            Beli Sekarang
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $promoProducts->links() }}
                    </div>
                @endif

                <div class="mt-10 bg-white border border-gray-100 rounded-xl p-4 grid grid-cols-2 lg:grid-cols-4 gap-4 shadow-sm">
                    <div class="flex items-center gap-2.5 justify-center lg:justify-start">
                        <div class="p-1.5 bg-pink-50 text-pink-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">100% Produk Original</h4>
                            <p class="text-[9px] text-gray-400 leading-none">Garansi uang kembali</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 justify-center lg:justify-start">
                        <div class="p-1.5 bg-pink-50 text-pink-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 5H4a1 1 0 00-1 1v11h10V5zM13 7h6l3 3v7h-9V7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">Gratis Ongkir</h4>
                            <p class="text-[9px] text-gray-400 leading-none">Seluruh Indonesia</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 justify-center lg:justify-start">
                        <div class="p-1.5 bg-pink-50 text-pink-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">Pembayaran Aman</h4>
                            <p class="text-[9px] text-gray-400 leading-none">Verifikasi sistem</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5 justify-center lg:justify-start">
                        <div class="p-1.5 bg-pink-50 text-pink-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-800">Customer Service</h4>
                            <p class="text-[9px] text-gray-400 leading-none">08.00 - 21.00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timers = document.querySelectorAll('[id^="countdown-"]');
        timers.forEach(timer => {
            const endTimeStr = timer.getAttribute('data-endtime').replace(/-/g, "/");
            const endTime = new Date(endTimeStr).getTime();
            const textSpan = timer.querySelector('.timer-text');

            const interval = setInterval(function () {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance < 0) {
                    clearInterval(interval);
                    textSpan.innerHTML = "00:00:00";
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                textSpan.innerHTML = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }, 1000);
        });
    });
</script>
</x-app-layout>