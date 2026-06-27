<nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
    <div class="w-full bg-pink-600 text-white text-[11px] py-2 px-4 sm:px-6 lg:px-8 flex justify-between items-center font-medium">
        <div class="flex items-center space-x-1">
            <span>🚚 Gratis Ongkir Seluruh Indonesia</span>
        </div>
        <div class="hidden md:block animate-pulse">⚡ Flash Sale Hingga 70%</div>
        <div class="flex items-center space-x-4">
            <a href="#" class="hover:underline">Bantuan</a>
            <a href="#" class="hover:underline">Lacak Pesanan</a>
            <span class="cursor-pointer flex items-center space-x-1">
                <span>ID</span>
                <svg class="w-2 h-2 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
            </span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
        
        <div class="shrink-0 flex items-center">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="text-xl font-black tracking-wider text-pink-600 flex items-center select-none">
                LACERA <span class="text-xs font-light text-gray-400 ml-2 pl-2 border-l border-gray-300 tracking-normal">OFFICIAL STORE</span>
            </a>
        </div>

        <form action="{{ route('category.index') }}" method="GET" class="flex-1 max-w-xl relative hidden md:block m-0">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk Lacera..." class="w-full text-xs pl-4 pr-12 py-2 border border-gray-200 rounded-full focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 shadow-sm">
            <button type="submit" class="absolute right-1 top-1 bottom-1 bg-pink-600 text-white px-4 rounded-full text-xs hover:bg-pink-700 transition flex items-center justify-center">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </form>

        <div class="flex items-center space-x-6">
            
            @auth
                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 text-xs font-bold text-gray-700 hover:text-pink-600 focus:outline-none transition py-1">
                                <div class="w-5 h-5 rounded-full border border-gray-300 flex items-center justify-center text-gray-500 bg-gray-50">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span>Halo, {{ Auth::user()->name }}</span>
                                <svg class="fill-current h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
                <div class="flex items-center space-x-3 text-xs font-bold text-gray-700">
                    <a href="{{ route('login') }}" class="hover:text-pink-600 transition">Masuk</a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('register') }}" class="hover:text-pink-600 transition">Daftar</a>
                </div>
            @endauth

            <a href="{{ route('cart.index') }}" class="relative flex items-center space-x-1 text-xs font-bold text-gray-700 hover:text-pink-600 transition">
                <div class="relative py-1">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    
                    @if(($cartCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-2 bg-pink-600 text-white text-[9px] font-extrabold px-1 py-0.5 rounded-full leading-none">
                            {{ $cartCount }}
                        </span>
                    @endif
                </div>
                <span class="hidden sm:inline">Keranjang</span>
            </a>

            <div class="flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-100 py-2.5 bg-white hidden sm:block shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center space-x-8 text-xs font-bold text-gray-600">
            
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="pb-1 transition {{ request()->routeIs('dashboard') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Beranda
            </a>

            @auth
            <a href="{{ route('category.index') }}" class="pb-1 transition {{ request()->routeIs('category.*') || request()->is('kategori*') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Kategori
            </a>

            <a href="{{ route('promo.index') }}" class="pb-1 transition {{ request()->routeIs('promo.index') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Promo
            </a>
            
            <a href="{{ route('best-seller.index') }}" class="pb-1 transition {{ request()->routeIs('best-seller.index') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Best Seller
            </a>
            
            <a href="{{ route('bundle.index') }}" class="pb-1 transition {{ request()->routeIs('bundle.index') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Bundle
            </a>

            <a href="{{ route('review.index') }}" class="pb-1 transition {{ request()->routeIs('review.index') ? 'text-pink-600 border-b-2 border-pink-600' : 'hover:text-pink-600' }}">
                Review
            </a>

            <a href="{{ route('orders.index') }}" class="hover:text-pink-600 transition">Lihat Pesanan</a>
            @endauth
            <a href="{{ route('track.index') }}" class="hover:text-pink-600 transition">Lacak Pesanan</a>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 bg-white shadow-inner">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <form action="{{ route('category.index') }}" method="GET" class="relative my-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk Lacera..." class="w-full text-xs pl-4 pr-10 py-2 border border-gray-200 rounded-full focus:outline-none">
                <button type="submit" class="absolute right-3 top-2 text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </form>
            
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('category.index')" :active="request()->routeIs('category.*') || request()->is('kategori*')">
                {{ __('Kategori') }}
            </x-responsive-nav-link>
            
            <!-- 🔥 UPDATE: Tautan Mobile Promo Diperbaiki -->
            <x-responsive-nav-link :href="route('promo.index')" :active="request()->routeIs('promo.index')">
                {{ __('Promo') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('best-seller.index')" :active="request()->routeIs('best-seller.index')">
                {{ __('Best Seller') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('bundle.index')" :active="request()->routeIs('bundle.index')">
                {{ __('Bundle') }}
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
                <div class="px-4">
                    <div class="font-medium text-sm text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-gray-200 bg-gray-50 px-4 space-y-2">
                <x-responsive-nav-link :href="route('login')">{{ __('Masuk') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">{{ __('Daftar') }}</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>