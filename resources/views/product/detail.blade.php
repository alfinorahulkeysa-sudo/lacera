<x-app-layout>
    <div class="container mx-auto px-4 py-6 max-w-7xl sm:px-6 lg:px-8 font-sans antialiased text-gray-800">
        
        <nav class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-6 bg-gray-50/80 p-3 rounded-xl border border-gray-100">
            <a href="/" class="hover:text-[#DE005E] transition font-medium">Beranda</a> 
            <span class="text-gray-300">/</span> 
            <a href="/kategori" class="hover:text-[#DE005E] transition font-medium">Kategori</a> 
            <span class="text-gray-300">/</span> 
            <span class="text-gray-800 font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-5 space-y-4">
                <div class="relative border border-gray-100 rounded-2xl p-6 bg-[#FFF5F8] shadow-sm aspect-square flex items-center justify-center overflow-hidden group">
                    <span class="absolute top-4 left-4 bg-[#DE005E] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm z-10">
                        Best Seller
                    </span>
                    
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition duration-300">
                </div>
                
                <div class="flex gap-2.5 overflow-x-auto py-1">
                    <div class="w-20 h-20 border-2 border-[#DE005E] rounded-xl p-1 bg-white cursor-pointer shadow-xs flex-shrink-0">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain mix-blend-multiply rounded-lg">
                    </div>
                    <div class="w-20 h-20 border border-gray-200 opacity-60 hover:opacity-100 rounded-xl p-1 bg-white cursor-pointer shadow-xs flex-shrink-0 transition">
                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain mix-blend-multiply rounded-lg grayscale">
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-pink-50 text-[#DE005E] text-[10px] font-bold px-3 py-1 rounded-full border border-pink-100 uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#DE005E]"></span>
                        Lacera Official Store
                    </span>
                    
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 mt-3 leading-tight tracking-tight">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="flex items-center gap-2.5 mt-3 text-sm text-gray-600 bg-gray-50/50 py-1 px-2 rounded-lg inline-flex">
                        <div class="flex text-amber-400 font-bold">⭐ {{ number_format($avgRating ?? 5.0, 1) }}</div>
                        <span class="text-gray-300">|</span>
                        <a href="#ulasan-produk" class="hover:underline cursor-pointer text-pink-600 font-medium">{{ $reviewCount ?? 0 }} Ulasan</a>
                        <span class="text-gray-300">|</span>
                        <span class="text-gray-700">Terjual <span class="font-semibold text-gray-900">{{ $product->sold_count ?? '33.9K' }}</span></span>
                    </div>
                </div>

                @php
                    $discountPercent = $product->promo ? $product->promo->value : 0;
                    $discountedPrice = $product->price - ($product->price * ($discountPercent / 100));
                @endphp

                <div class="bg-gradient-to-br from-[#FFF5F8] to-white border border-pink-100/70 p-5 rounded-2xl shadow-xs">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Harga Special Lacera</div>
                    <div class="flex items-center flex-wrap gap-3">
                        <span class="text-3xl font-black text-[#DE005E] tracking-tight">
                            Rp{{ number_format($discountedPrice, 0, ',', '.') }}
                        </span>
                        @if($discountPercent > 0)
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-lg border border-red-200">
                                {{ $discountPercent }}% OFF
                            </span>
                        @endif
                    </div>
                    
                    @if($discountPercent > 0)
                        <div class="flex items-center gap-1.5 mt-1.5 text-sm text-gray-400">
                            <span>Harga Normal:</span>
                            <span class="line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-3 gap-2 py-1 text-[11px] font-semibold text-emerald-700">
                    <div class="flex items-center gap-1 bg-emerald-50/60 p-2 rounded-xl border border-emerald-100/50 justify-center">
                        🛡️ 100% Ori
                    </div>
                    <div class="flex items-center gap-1 bg-emerald-50/60 p-2 rounded-xl border border-emerald-100/50 justify-center">
                        ✨ BPOM
                    </div>
                    <div class="flex items-center gap-1 bg-emerald-50/60 p-2 rounded-xl border border-emerald-100/50 justify-center">
                        🌱 Halal
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-5 space-y-5">
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2.5 text-xs uppercase tracking-wider flex items-center gap-2 text-gray-400">
                            <span class="w-1.5 h-3 bg-[#DE005E] rounded-full inline-block"></span> Kandungan Utama
                        </h3>
                        <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 text-gray-700 text-sm leading-relaxed font-medium">
                            {{ $product->ingredients ?? 'Niacinamide, Kojic Acid, Collagen, Glutathione' }}
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-bold text-gray-900 mb-2 text-xs uppercase tracking-wider flex items-center gap-2 text-gray-400">
                            <span class="w-1.5 h-3 bg-[#DE005E] rounded-full inline-block"></span> Manfaat Produk
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed pl-3.5 border-l-2 border-gray-200">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 lg:sticky lg:top-24">
                <div class="border border-gray-200 rounded-2xl p-5 bg-white shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                        <h3 class="font-bold text-gray-900 text-sm">Atur Jumlah & Aturan</h3>
                        <span class="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Ready Stock
                        </span>
                    </div>
                    
                    <form action="{{ route('cart.store', $product->id) }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pilih Ukuran</label>
                            <div class="flex gap-2">
                                <button type="button" class="px-3.5 py-2 text-xs font-bold border-2 border-[#DE005E] text-[#DE005E] bg-[#FFF5F8] rounded-xl transition">
                                    200 ml
                                </button>
                                <button type="button" class="px-3.5 py-2 text-xs font-semibold border border-gray-200 text-gray-400 rounded-xl cursor-not-allowed opacity-50 bg-gray-50" disabled>
                                    100 ml
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Pesanan</label>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden p-1 bg-gray-50 shadow-inner">
                                    <button type="button" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg font-extrabold text-gray-500 hover:text-[#DE005E] hover:bg-pink-50 shadow-xs transition" onclick="decreaseQty()">-</button>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-12 text-center bg-transparent border-none focus:ring-0 text-sm font-bold text-gray-800 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none p-0" readonly>
                                    <button type="button" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg font-extrabold text-gray-500 hover:text-[#DE005E] hover:bg-pink-50 shadow-xs transition" onclick="increaseQty()">+</button>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">Sisa 100+ pcs</span>
                            </div>
                        </div>

                        <div class="space-y-2.5 pt-2">
                            <button type="submit" class="w-full bg-[#FFF5F8] border border-[#DE005E] text-[#DE005E] font-bold py-3 rounded-xl hover:bg-[#FFE5EE] transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-xs">
                                🛒 Tambah Keranjang
                            </button>
                            
                            <button type="submit" class="w-full bg-[#DE005E] text-white font-bold py-3 rounded-xl hover:bg-[#C20052] transition text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-md shadow-pink-100">
                                Beli Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- BAGIAN ULASAN PRODUK CUSTOMER --}}
        <div class="mt-16 border-t border-gray-100 pt-10" id="ulasan-produk">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 uppercase tracking-tight flex items-center gap-2">
                        <span class="w-2 h-5 bg-[#DE005E] rounded-md inline-block"></span> Ulasan & Rating Pembeli
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Ulasan nyata dari pelanggan yang telah membeli {{ $product->name }}</p>
                </div>

                <div class="flex items-center gap-4 bg-pink-50/70 border border-pink-100 px-5 py-3 rounded-2xl">
                    <div class="text-2xl font-black text-[#DE005E]">{{ number_format($avgRating ?? 5.0, 1) }}</div>
                    <div>
                        <div class="flex text-amber-400 text-xs">⭐⭐⭐⭐⭐</div>
                        <span class="text-[11px] text-gray-500 font-semibold">Berdasarkan {{ $reviewCount ?? 0 }} ulasan</span>
                    </div>
                </div>
            </div>

            {{-- Flash Alert Ulasan --}}
            @if(session('review_success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
                    ⭐
                </div>
                <p class="text-sm font-semibold text-green-700">{{ session('review_success') }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Form Tulis Ulasan --}}
                <div class="lg:col-span-4 bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                    <h3 class="font-bold text-gray-900 text-sm mb-1 flex items-center gap-2">
                        ✍️ Tulis Ulasan Anda
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Bagikan pengalaman Anda menggunakan produk ini.</p>

                    @auth
                    <form action="{{ route('review.store', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Rating Bintang</label>
                            <select name="rating" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:outline-none focus:border-[#DE005E]">
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                <option value="2">⭐⭐ (2 - Kurang)</option>
                                <option value="1">⭐ (1 - Kecewa)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Komentar & Pengalaman</label>
                            <textarea name="comment" rows="3" required placeholder="Ceritakan hasil dan tekstur produk di kulitmu..." class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DE005E]"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-[#DE005E] hover:bg-[#C20052] text-white font-bold py-2.5 rounded-xl transition text-xs shadow-md shadow-pink-100">
                            Kirim Ulasan
                        </button>
                    </form>
                    @else
                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                        <p class="text-xs text-gray-600 mb-3">Silakan masuk ke akun Anda terlebih dahulu untuk memberikan ulasan.</p>
                        <a href="{{ route('login') }}" class="inline-block bg-[#DE005E] text-white text-xs font-bold px-4 py-2 rounded-xl hover:bg-[#C20052] transition">
                            Masuk Sekarang
                        </a>
                    </div>
                    @endauth
                </div>

                {{-- Daftar Ulasan --}}
                <div class="lg:col-span-8 space-y-4">
                    @forelse($reviews as $rev)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-[#DE005E] font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr($rev->user->name ?? 'C', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">{{ $rev->user->name ?? 'Pembeli Lacera' }}</h4>
                                    <span class="text-[10px] text-emerald-600 font-semibold"><i class="fas fa-check-circle"></i> Pembeli Terverifikasi</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="flex text-amber-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $rev->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-gray-400 mt-0.5 block">{{ $rev->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-700 leading-relaxed mt-2 pl-10">
                            {{ $rev->comment }}
                        </p>
                    </div>
                    @empty
                    <div class="bg-gray-50/70 rounded-2xl p-8 text-center border border-gray-100">
                        <p class="text-xs text-gray-500 font-medium">Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

        <div class="mt-20 border-t border-gray-100 pt-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-extrabold text-gray-900 uppercase tracking-tight flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#DE005E] rounded-md inline-block"></span> Produk Terkait
                </h2>
                <a href="/kategori" class="text-xs font-bold text-[#DE005E] hover:underline transition">Lihat Semua →</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($relatedProducts as $related)
                    @php
                        $relDiscount = $related->promo ? $related->promo->value : 0;
                        $relPrice = $related->price - ($related->price * ($relDiscount / 100));
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl p-3 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition duration-300 relative group overflow-hidden">
                        <a href="{{ route('product.show', $related->id) }}" class="block flex-1 cursor-pointer">
                            
                            <div class="bg-gray-50/70 rounded-xl p-3 mb-3 aspect-square flex items-center justify-center overflow-hidden border border-gray-50/50 relative">
                                @if($relDiscount > 0)
                                    <span class="absolute top-2 left-2 bg-[#DE005E] text-white font-extrabold text-[8px] tracking-wider px-2 py-0.5 rounded-md shadow-sm z-10 uppercase">
                                        {{ $relDiscount }}% OFF
                                    </span>
                                @endif
                                <img src="{{ asset('storage/' . $related->image) }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition duration-300">
                            </div>
                            
                            <div class="space-y-1 px-1">
                                <h4 class="text-xs font-bold text-gray-800 line-clamp-2 leading-tight group-hover:text-[#DE005E] transition h-8">
                                    {{ $related->name }}
                                </h4>
                                <div class="pt-1 flex items-baseline gap-1.5 flex-wrap">
                                    <p class="text-sm font-black text-[#DE005E]">Rp{{ number_format($relPrice, 0, ',', '.') }}</p>
                                    @if($relDiscount > 0)
                                        <p class="text-[10px] text-gray-400 line-through">Rp{{ number_format($related->price, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        function increaseQty() {
            let qty = document.getElementById('quantity');
            qty.value = parseInt(qty.value) + 1;
        }
        
        function decreaseQty() {
            let qty = document.getElementById('quantity');
            if (parseInt(qty.value) > 1) {
                qty.value = parseInt(qty.value) - 1;
            }
        }
    </script>
</x-app-layout>