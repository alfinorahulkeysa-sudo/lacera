<x-app-layout>
    <div class="bg-[#fce7f3] relative overflow-hidden border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 flex flex-col md:flex-row items-center justify-between relative min-h-[380px]">
            <div class="md:w-1/2 text-center md:text-left z-10 space-y-4 pr-0 md:pr-4">
                <h2 class="text-[#d62175] font-extrabold tracking-widest text-xs bg-white/80 inline-block px-4 py-1 rounded-full uppercase shadow-2xs">
                    LACERA 
                </h2>
                <h3 class="text-sm text-[#d62175] font-bold tracking-widest bg-pink-200/50 inline-block px-3 py-0.5 rounded">7 ICONIC PRODUCTS</h3>
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight">
                    COMPLETE SOLUTION <br> 
                    <span class="text-[#d62175] font-extrabold text-2xl md:text-3xl block mt-1">
                        FOR HEALTHY & GLOWING SKIN
                    </span>
                </h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 pt-2 text-[10px] text-gray-600 font-medium">
                    <span>✨ Brightening & Glowing Skin</span>
                    <span>💧 Nourishing & Moisturizing</span>
                    <span>🛡️ Protect Skin Barrier</span>
                    <span>🌿 Natural Ingredients</span>
                </div>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 pt-4">
                    <a href="{{ url('/kategori') }}" class="bg-[#d62175] hover:bg-[#b81760] text-white font-bold py-2.5 px-6 rounded shadow-md text-xs transition">
                        Belanja Sekarang
                    </a>
                    <a href="{{ url('/promo') }}" class="bg-white border border-pink-200 text-[#d62175] hover:bg-pink-50 font-bold py-2.5 px-6 rounded shadow-xs text-xs transition">
                        Lihat Promo
                    </a>
                </div>
            </div>
            
            <div class="md:w-1/2 w-full mt-8 md:mt-0 flex justify-center z-10 h-[300px] md:h-[400px]">
                <img src="{{ asset('storage/banners/banner-atas.jpeg') }}" alt="Lacera Top Banner" class="w-full h-full object-cover rounded-2xl shadow-xl border border-pink-200/50">
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-100 py-4 shadow-2xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center text-[11px] font-bold text-gray-700">
                <div class="flex items-center justify-center gap-2 border-r border-gray-100 last:border-0"><span class="text-base">✨</span> Brightening & Glowing</div>
                <div class="flex items-center justify-center gap-2 border-r border-gray-100 last:border-0"><span class="text-base">💧</span> Nourishing & Moisturizing</div>
                <div class="flex items-center justify-center gap-2 border-r border-gray-100 last:border-0"><span class="text-base">🛡️</span> Protect & Strengthen Skin</div>
                <div class="flex items-center justify-center gap-2 border-r border-gray-100 last:border-0"><span class="text-base">🌿</span> Safe Natural Ingredients</div>
                <div class="flex items-center justify-center gap-2 border-r border-gray-100 last:border-0"><span class="text-base">🐰</span> Aman untuk Semua Jenis Kulit</div>
            </div>
        </div>
    </div>
    
    <div class="bg-gray-50/70 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-black text-gray-800 tracking-wider uppercase">KATEGORI FAVORIT</h3>
                            <a href="{{ url('/kategori') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">
                            @foreach($categories as $cat)
                                <a href="{{ url('/kategori?type='.$cat->slug) }}" class="flex flex-col items-center p-3 border border-gray-100 rounded-xl hover:border-pink-200 transition bg-white shadow-2xs group text-center">
                                    <span class="text-2xl mb-2 group-hover:scale-110 transition duration-200">
                                        @switch(Str::slug($cat->name))
                                            @case('body-lotion') 🧴 @break
                                            @case('body-serum') 💧 @break
                                            @case('body-wash') 🧼 @break
                                            @case('collagen-soap') 🧼 @break
                                            @case('lip-treatment') 💄 @break
                                            @default 🎁
                                        @endswitch
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-600 group-hover:text-[#d62175] block truncate w-full">{{ $cat->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xs font-black text-gray-800 tracking-wider uppercase">BEST SELLER</h3>
                            <a href="{{ url('/best-seller') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach($produkBestSeller as $product)
                                <div class="bg-white border border-gray-100 rounded-xl p-3 flex flex-col justify-between hover:shadow-sm transition">
                                    <div>
                                        <div class="bg-gray-50 rounded-lg p-2 mb-2">
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-24 object-contain rounded-md mx-auto" alt="{{ $product->name }}">
                                        </div>
                                        <h4 class="text-[11px] font-bold text-gray-800 line-clamp-2 leading-tight">{{ $product->name }}</h4>
                                        <p class="text-[10px] text-yellow-500 font-bold mt-1">⭐ 4.9 <span class="text-gray-400 font-normal">({{ $product->sold_count ?? 0 }})</span></p>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-xs font-black text-[#d62175]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                        <button class="w-full bg-[#d62175] hover:bg-[#b81760] text-white text-[10px] font-bold py-1.5 rounded-lg mt-2 transition shadow-2xs">+ Keranjang</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    
                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-red-100">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xs font-black text-red-600 uppercase tracking-wider flex items-center gap-1">🔥 FLASH SALE</h3>
                            <a href="{{ url('/promo') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <span class="bg-red-600 text-white text-xs font-black px-2 py-0.5 rounded shadow-2xs">06</span><span class="text-red-600 font-bold">:</span>
                            <span class="bg-red-600 text-white text-xs font-black px-2 py-0.5 rounded shadow-2xs">15</span><span class="text-red-600 font-bold">:</span>
                            <span class="bg-red-600 text-white text-xs font-black px-2 py-0.5 rounded shadow-2xs">44</span>
                        </div>

                        @foreach($produkPromo as $product)
                            <div class="space-y-2 border-b border-gray-100 pb-3 last:border-0 last:pb-0 mb-3 last:mb-0">
                                <div class="flex gap-3 bg-gray-50/50 p-2 rounded-xl border border-gray-100 relative overflow-hidden">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-contain bg-white rounded-lg border" alt="{{ $product->name }}">
                                    <div class="overflow-hidden flex flex-col justify-center flex-1">
                                        <h4 class="text-xs font-bold text-gray-800 truncate">{{ $product->name }}</h4>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-sm font-black text-red-600">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                            <span class="bg-red-100 text-red-600 text-[9px] font-black px-1.5 py-0.2 rounded-full">PROMO</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-red-500 to-pink-500 h-1.5 rounded-full" style="width: 75%"></div>
                                    </div>
                                    <div class="flex justify-between items-center text-[10px] text-gray-500 pt-1">
                                        <span>Terjual {{ $product->sold_count ?? 0 }}</span>
                                        <button class="bg-[#d62175] hover:bg-[#b81760] text-white font-black px-4 py-1 rounded-lg text-[10px] transition">Beli Sekarang</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-purple-100">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xs font-black text-purple-700 uppercase tracking-wider">📦 PAKET HEMAT LACERA</h3>
                            <a href="{{ url('/bundle') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="border border-purple-100 rounded-xl p-3 bg-purple-50/20 text-center relative">
                            <div class="flex justify-center items-center gap-2 mb-2">
                                <img src="https://encrypted-tbn2.gstatic.com/shopping?q=tbn:ANd9GcTtKk-09m7kA6TH9azH4G8Tdm15Ea9R1kjSnaUqBK0IILRbUJIZjyeviNoBHUQmo1xFOLEUS36bqjsnuwPuO9yqtpvXfvgM4vFKXreEIlM&usqp=CAc" class="w-12 h-12 object-contain bg-white rounded border">
                                <span class="text-purple-400 font-bold text-sm">+</span>
                                <img src="https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcQMUKafvoDJBsX0eqs1WFrkrXRq0qdkDzdxymnWowuuYIcFnj64EEPsLYPTrXly1SJmW87gJJmrvPIQmhCYCR_T5Sq6EHNpDgvTnI1uZufaYm2BQ6adWy6m&usqp=CAc" class="w-12 h-12 object-contain bg-white rounded border">
                            </div>
                            <h4 class="text-[11px] font-bold text-gray-800">Natural Collagen Soap + Natural Lip Treatment</h4>
                            <div class="flex items-center justify-center gap-2 mt-1">
                                <span class="text-sm font-black text-[#d62175]">Rp113.998</span>
                                <span class="bg-purple-100 text-purple-700 text-[9px] font-black px-1.5 py-0.2 rounded-full">45% OFF</span>
                            </div>
                            <p class="text-[10px] text-gray-400 line-through">Rp207.647</p>
                            <button class="w-full bg-gradient-to-r from-[#d62175] to-purple-600 text-white text-[11px] font-bold py-2 rounded-lg mt-3 shadow-xs">Tambah Ke Keranjang</button>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider">✨ PRODUK TERBARU</h3>
                            <a href="{{ url('/kategori?sort=terbaru') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($produkTerbaru->take(3) as $product)
                                <div class="text-center p-2 border border-gray-50 rounded-lg bg-gray-50/30 flex flex-col justify-between items-center">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="h-10 object-contain mb-1" alt="{{ $product->name }}">
                                    <p class="text-[9px] font-bold text-gray-700 truncate w-full">{{ $product->name }}</p>
                                    <p class="text-[10px] font-black text-[#d62175]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-2xs border border-gray-100">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-wider">💬 APA KATA MEREKA?</h3>
                            <a href="{{ url('/review') }}" class="text-[#d62175] hover:underline text-xs font-bold">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100 text-[10px] flex flex-col justify-between">
                                <div>
                                    <div class="text-yellow-400 text-[8px] mb-1">⭐⭐⭐⭐⭐</div>
                                    <p class="text-gray-600 italic leading-tight">"Sabunnya bikin kulit cerah cepat, wangi mewah!"</p>
                                </div>
                                <p class="text-[9px] font-bold text-gray-700 mt-2">Rina <span class="text-green-600 font-normal text-[8px]">✓ Verified</span></p>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100 text-[10px] flex flex-col justify-between">
                                <div>
                                    <div class="text-yellow-400 text-[8px] mb-1">⭐⭐⭐⭐⭐</div>
                                    <p class="text-gray-600 italic leading-tight">"Body lotion cepat meresap dan lembab seharian."</p>
                                </div>
                                <p class="text-[9px] font-bold text-gray-700 mt-2">Desi <span class="text-green-600 font-normal text-[8px]">✓ Verified</span></p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 pt-2">
                
                <div class="lg:col-span-8 bg-[#fce7f3] rounded-xl flex flex-row items-center justify-between border border-pink-200 relative overflow-hidden min-h-[140px] shadow-2xs">
                    <div class="z-10 flex flex-col justify-center space-y-1 w-1/2 p-6 md:pl-8">
                        <h3 class="text-2xl font-black text-[#d62175] tracking-wide leading-none">BUY 2 GET 1</h3>
                        <p class="text-[11px] font-bold text-gray-700 uppercase tracking-wider">INSTANT GLOW UP YOUR SKIN</p>
                        <div class="pt-2">
                            <a href="{{ url('/promo') }}" class="bg-[#d62175] hover:bg-[#b81760] text-white text-[10px] font-bold py-2 px-5 rounded transition shadow-xs inline-block">
                                Ambil Promo
                            </a>
                        </div>
                    </div>
                    
                    <div class="absolute right-0 top-0 bottom-0 w-1/2 z-0">
                        <div class="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#fce7f3] to-transparent z-10"></div>
                        <img src="{{ asset('storage/banners/banner-bawah.jpeg') }}" class="w-full h-full object-cover" alt="Promo Buy 2 Get 1">
                    </div>
                </div>

                <div class="lg:col-span-4 bg-white p-5 rounded-xl shadow-2xs border border-gray-100 flex flex-row items-center justify-between min-h-[140px] relative overflow-hidden">
                    <div class="flex-1 z-10">
                        <h3 class="text-xs font-black text-gray-800 tracking-wider uppercase">CEK STATUS PESANAN</h3>
                        <p class="text-[10px] text-gray-400 mt-0.5 mb-3">Lacak pesananmu dengan mudah</p>
                        
                        <form action="{{ url('/tracking') }}" method="GET" class="flex gap-2 w-full max-w-sm">
                            <input type="text" name="order_code" placeholder="Masukkan nomor pesanan" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-[11px] focus:outline-none focus:border-[#d62175] focus:ring-0 shadow-inner text-gray-700 placeholder-gray-300">
                            <button type="submit" class="bg-[#d62175] hover:bg-[#b81760] text-white text-[11px] font-bold px-4 py-2 rounded-lg transition shadow-xs whitespace-nowrap">
                                Cek Status
                            </button>
                        </form>
                    </div>
                    <div class="flex flex-col items-center justify-center opacity-80 pl-2 select-none pointer-events-none z-10">
                        <span class="text-4xl filter drop-shadow-xs">🚚</span>
                        <span class="text-[18px] -mt-2 filter drop-shadow-xs text-[#d62175]">📍</span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>