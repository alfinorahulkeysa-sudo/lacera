<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen" x-data="cartComponent()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-black text-gray-800 mb-8 tracking-wide uppercase">
                Keranjang Belanja 🛒
            </h2>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-xs font-semibold rounded-r-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($cartItems->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="w-24 h-24 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-6 text-pink-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium mb-6 text-sm">Wah, keranjang belanjamu masih kosong nih!</p>
                    <a href="{{ route('best-seller.index') }}" class="inline-flex items-center justify-center bg-pink-600 text-white text-xs font-bold px-6 py-3 rounded-xl uppercase tracking-wider hover:bg-pink-700 transition shadow-md shadow-pink-100">
                        Mulai Belanja
                    </a>
                </div>
            @endif

            @if(!$cartItems->isEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            @php
                                // Menghitung harga diskon per item
                                $discountPercent = $item->product->promo ? $item->product->promo->value : 0;
                                $discountedPrice = $item->product->price - ($item->product->price * ($discountPercent / 100));
                            @endphp
                            
                            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex gap-5 relative group transition hover:shadow-md">
                                
                                @if($discountPercent > 0)
                                    <span class="absolute -top-2 -left-2 bg-pink-600 text-white text-[9px] font-extrabold px-2 py-1 rounded shadow-sm z-10">
                                        {{ $discountPercent }}% OFF
                                    </span>
                                @endif

                                <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-100 relative">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50 text-xs">No Image</div>
                                    @endif
                                </div>

                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm tracking-tight mb-1 group-hover:text-pink-600 transition">
                                            {{ $item->product->name }}
                                        </h4>
                                        <p class="text-[11px] text-gray-400 mb-1">Stok Tersedia</p>
                                        
                                        <div class="flex items-baseline gap-2 mt-1">
                                            <p class="text-sm font-black text-pink-600">
                                                Rp {{ number_format($discountedPrice, 0, ',', '.') }}
                                            </p>
                                            @if($discountPercent > 0)
                                                <p class="text-[10px] text-gray-400 line-through">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between mt-3">
                                        
                                        <form action="{{ route('cart.update') }}" method="POST" class="m-0 flex items-center border border-gray-200 rounded-lg p-0.5 bg-gray-50 shadow-inner overflow-hidden">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            
                                            <button type="button" onclick="decrementQty(this)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-pink-600 font-bold transition rounded-md hover:bg-white select-none">
                                                -
                                            </button>
                                            
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" readonly 
                                                   class="w-10 text-center bg-transparent border-none p-0 text-xs font-bold text-gray-700 focus:ring-0 select-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                            
                                            <button type="button" onclick="incrementQty(this)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:text-pink-600 font-bold transition rounded-md hover:bg-white select-none">
                                                +
                                            </button>
                                        </form>

                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-rose-600 p-2 transition duration-200" title="Hapus dari keranjang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    @php
                        // Kalkulasi Total untuk Ringkasan Belanja
                        $totalHargaAsli = 0;
                        $totalBayar = 0;

                        foreach($cartItems as $item) {
                            $hargaAsli = $item->product->price * $item->quantity;
                            $persenDiskon = $item->product->promo ? $item->product->promo->value : 0;
                            $hargaSetelahDiskon = ($item->product->price - ($item->product->price * ($persenDiskon / 100))) * $item->quantity;

                            $totalHargaAsli += $hargaAsli;
                            $totalBayar += $hargaSetelahDiskon;
                        }

                        $totalDiskon = $totalHargaAsli - $totalBayar;
                        
                        // Menyimpan total harga ke session agar terbaca di CheckoutController
                        session(['cart_total' => $totalBayar]);
                    @endphp

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24">
                        <h3 class="text-sm font-black text-gray-800 uppercase tracking-wider mb-5 pb-3 border-b border-gray-100">
                            Ringkasan Belanja
                        </h3>
                        
                        <div class="space-y-3 mb-6 text-xs font-medium text-gray-500">
                            <div class="flex justify-between">
                                <span>Total Barang</span>
                                <span class="text-gray-800 font-bold">{{ $cartItems->sum('quantity') }} Item</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Harga Asli</span>
                                <span class="text-gray-800 font-bold">
                                    Rp {{ number_format($totalHargaAsli, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            @if($totalDiskon > 0)
                                <div class="flex justify-between text-emerald-600 bg-emerald-50 p-2 rounded-lg font-bold">
                                    <span>Promo Diskon Lacera</span>
                                    <span>- Rp {{ number_format($totalDiskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-4 mb-6 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-700">Total Bayar:</span>
                            <span class="text-lg font-black text-pink-600">
                                Rp {{ number_format($totalBayar, 0, ',', '.') }}
                            </span>
                        </div>

                        <a href="{{ route('checkout') }}" class="w-full bg-pink-600 text-white text-xs font-bold py-3.5 rounded-xl uppercase tracking-wider hover:bg-pink-700 transition shadow-md shadow-pink-100 flex items-center justify-center space-x-2">
                            <span>Lanjut ke Pembayaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                </div>
            @endif
        </div>
    </div>

    <script>
        function incrementQty(button) {
            let form = button.closest('form');
            let input = form.querySelector('input[name="quantity"]');
            
            input.value = parseInt(input.value) + 1;
            
            form.style.opacity = '0.5';
            form.style.pointerEvents = 'none';
            form.submit();
        }

        function decrementQty(button) {
            let form = button.closest('form');
            let input = form.querySelector('input[name="quantity"]');
            let currentValue = parseInt(input.value);
            
            if (currentValue > 1) {
                input.value = currentValue - 1;
                form.style.opacity = '0.5';
                form.style.pointerEvents = 'none';
                form.submit();
            }
        }
    </script>
</x-app-layout>