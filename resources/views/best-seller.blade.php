<x-app-layout>
    <div class="py-8 bg-[#f9fafb] min-h-screen font-sans">
        <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <a href="/" class="hover:text-pink-600">🏠 Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-gray-800 font-medium">Best Seller</span>
            </nav>

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b border-gray-100 pb-5 mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight uppercase">Best Seller</h1>
                    <p class="mt-1 text-xs text-gray-500">Produk terlaris pilihan pelanggan Lacera Official Store</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center gap-2 text-xs">
                    <span class="text-gray-500">Urutkan:</span>
                    <select class="rounded-lg border-gray-200 bg-white py-1.5 pl-3 pr-8 text-xs font-medium text-gray-700 focus:border-pink-500 focus:ring-pink-500">
                        <option>Terlaris</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="w-full lg:w-1/4 bg-white p-5 rounded-xl border border-gray-100 h-fit shadow-sm">
                    <form action="{{ route('best-seller.index') }}" method="GET" class="space-y-5">
                        
                        <div>
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Cari Produk</h3>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk..." 
                                   class="w-full rounded-lg border-gray-200 shadow-sm focus:border-pink-500 focus:ring-pink-500 text-xs py-2">
                        </div>

                        <div>
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Kategori</h3>
                            <div class="space-y-1">
                                <a href="{{ route('best-seller.index') }}" class="block text-xs p-2 rounded-lg font-medium {{ !request('category') ? 'bg-pink-50 text-pink-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                    📦 Semua Produk
                                </a>
                                @foreach($categories as $cat)
                                    <a href="{{ route('category.show', $cat->slug) }}" class="block text-xs p-2 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                                        🌸 {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Rentang Harga (Rp)</h3>
                            <div class="space-y-2">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Rp Min" 
                                       class="w-full rounded-lg border-gray-200 text-xs focus:border-pink-500 focus:ring-pink-500 py-2">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp Max" 
                                       class="w-full rounded-lg border-gray-200 text-xs focus:border-pink-500 focus:ring-pink-500 py-2">
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-2">Rating Minimal</h3>
                            <div class="space-y-2">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    <label class="flex items-center text-xs text-gray-600 cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $star }}" {{ request('rating') == $star ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-pink-600 focus:ring-pink-500 mr-2">
                                        <span class="text-yellow-400 font-bold mr-1">
                                            {!! str_repeat('★', $star) !!}{!! str_repeat('☆', 5 - $star) !!}
                                        </span>
                                        <span>{{ $star == 5 ? '(5)' : 'ke atas' }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-lg text-xs transition shadow-sm uppercase tracking-wider">
                                Terapkan Filter
                            </button>
                            @if(request()->anyFilled(['search', 'min_price', 'max_price', 'rating']))
                                <a href="{{ route('best-seller.index') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg text-xs transition">
                                    Reset Semua Filter
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="w-full lg:w-3/4">
                    @if($products->isEmpty())
                        <div class="bg-white p-16 rounded-xl text-center border border-gray-100">
                            <span class="text-4xl">🔍</span>
                            <h3 class="mt-4 text-sm font-bold text-gray-900">Produk Tidak Ditemukan</h3>
                            <p class="mt-1 text-xs text-gray-400">Silakan ganti kata kunci atau reset filter pencarian Anda.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4">
                            @foreach ($products as $product)
                                @php
                                    // Hitung Rangking Akurat Kumulatif
                                    $rank = $products->firstItem() + $loop->index;
                                    
                                    // Lingkaran warna badge nomor peringkat sesuai podium medali mockup
                                    $rankBadge = match($rank) {
                                        1 => 'bg-amber-400 text-white font-black',
                                        2 => 'bg-slate-400 text-white font-black',
                                        3 => 'bg-amber-600 text-white font-black',
                                        default => 'bg-white border border-gray-300 text-gray-600 font-semibold'
                                    };
                                @endphp

                                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden relative group hover:shadow-md transition flex flex-col justify-between p-3">
                                    
                                    {{-- LINK UTAMA MENUJU DETAIL PRODUK --}}
                                    {{-- Catatan: Sesuaikan nama rute 'products.show' atau parameternya ($product->slug) jika web.php Anda berbeda --}}
                                    <a href="{{ route('product.show', $product->id) }}" class="block flex-1 group">
                                        <div>
                                            <div class="w-full aspect-square bg-gray-50 rounded-lg overflow-hidden relative mb-2">
                                                @if($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300 text-[10px]">No Image</div>
                                                @endif

                                                <div class="absolute top-1.5 left-1.5 z-10 w-6 h-6 rounded-full flex items-center justify-center text-xs shadow-sm {{ $rankBadge }}">
                                                    {{ $rank }}
                                                </div>
                                            </div>

                                            <div class="space-y-1">
                                                <h2 class="text-[11px] font-bold text-gray-800 line-clamp-2 min-h-[32px] leading-tight group-hover:text-pink-600 transition">
                                                    {{ $product->name }}
                                                </h2>
                                                
                                                <div class="flex items-center text-[10px] text-gray-500">
                                                    <span class="text-yellow-400 font-bold mr-0.5">★</span>
                                                    <span class="font-bold text-gray-700">4.9</span>
                                                    <span class="text-gray-400 ml-0.5">(2.1K)</span>
                                                </div>

                                                <div class="pt-0.5">
                                                    <span class="inline-block bg-pink-50 text-pink-600 text-[9px] font-bold px-1.5 py-0.5 rounded">
                                                        Terlaris di {{ $product->category->name ?? 'Lacera' }}
                                                    </span>
                                                </div>

                                                <div class="pt-1">
                                                    <div class="text-xs font-extrabold text-pink-600">
                                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                                    </div>
                                                    <div class="flex items-center gap-1 text-[9px] text-gray-400 mt-0.5">
                                                        <span class="line-through">Rp{{ number_format($product->price * 2.5, 0, ',', '.') }}</span>
                                                        <span class="text-pink-600 font-bold">70% OFF</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    {{-- FORM TOMBOL AKSI (Tetap berada di luar jangkauan klik link detail) --}}
                                    <div class="mt-3 pt-2 border-t border-gray-50 flex items-center gap-1.5 relative z-10">
                                        <form action="{{ route('cart.store', $product->id) }}" method="POST" class="flex-1 m-0">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white text-[10px] font-bold py-1.5 px-2 rounded-md transition text-center uppercase">
                                                + Keranjang
                                            </button>
                                        </form>
                                        <button class="border border-gray-200 text-gray-400 hover:text-pink-600 hover:border-pink-200 p-1.5 rounded-md transition" title="Tambah Ke Wishlist">
                                            ♡
                                        </button>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 border-t border-gray-100 pt-4">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>