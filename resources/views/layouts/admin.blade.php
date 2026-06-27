<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacera Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .text-lacera { color: #e81c62; }
        .bg-lacera { background-color: #e81c62; }
        .bg-lacera-light { background-color: #fde8ef; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col h-full z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
        <div class="h-20 flex items-center justify-center border-b border-gray-50">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-lacera tracking-wide">LACERA</h1>
                <p class="text-[10px] text-gray-400 tracking-widest uppercase">Official Store</p>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-3 flex flex-col gap-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-home w-5 text-center"></i> Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-box w-5 text-center"></i> Kelola Produk
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.categories.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-tags w-5 text-center"></i> Kelola Kategori
            </a>

            {{-- 💡 UPDATE SINKRONISASI: Menghubungkan Rute Kelola Promo dengan Logika Aktif / Nyala --}}
            <a href="{{ route('admin.promos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.promos.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-ticket-alt w-5 text-center"></i> Kelola Promo
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <div class="flex items-center gap-3">
                    <i class="fas fa-shopping-bag w-5 text-center"></i> Kelola Pesanan
                </div>
                @php $pendingOrderCount = \App\Models\Order::whereIn('status', ['paid', 'processing'])->count(); @endphp
                @if($pendingOrderCount > 0)
                <span class="bg-lacera text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingOrderCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="flex items-center justify-between px-4 py-3 {{ request()->routeIs('admin.reviews.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <div class="flex items-center gap-3">
                    <i class="fas fa-star w-5 text-center"></i> Kelola Review
                </div>
                @php $pendingReviewCount = \App\Models\Review::where('is_approved', false)->count(); @endphp
                @if($pendingReviewCount > 0)
                <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingReviewCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-users w-5 text-center"></i> Kelola User
            </a>
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.reports') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-chart-bar w-5 text-center"></i> Laporan Penjualan
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.settings') ? 'bg-lacera-light text-lacera font-semibold' : 'text-gray-500 hover:bg-gray-50 hover:text-lacera font-medium' }} rounded-xl text-sm transition">
                <i class="fas fa-cog w-5 text-center"></i> Pengaturan
            </a>
            
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center gap-3 px-4 py-3 mt-auto text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-xl font-medium text-sm transition">
                <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
            </a>
        </div>

        <div class="p-4 mt-auto">
            <div class="bg-lacera-light rounded-2xl p-4 text-center">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <i class="fas fa-shopping-bag text-lacera"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Tingkatkan Penjualan</h4>
                <p class="text-[11px] text-gray-500 mb-3 leading-relaxed">Buat promo menarik untuk meningkatkan penjualan toko Anda.</p>
                
                {{-- 💡 UPDATE SINKRONISASI: Menghubungkan Tombol "Buat Promo" di Card ke Halaman Form Tambah --}}
                <a href="{{ route('admin.promos.create') }}" class="block w-full bg-lacera text-white text-xs font-bold py-2.5 rounded-lg hover:bg-rose-700 transition text-center">Buat Promo</a>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <header class="h-20 bg-white flex items-center justify-between px-8 border-b border-gray-100 z-10">
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-lacera">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-tight">Dashboard</h2>
                    <p class="text-xs text-gray-500">Selamat datang kembali, Admin Lacera 👋</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="hidden md:flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-600">
                    <i class="far fa-calendar-alt"></i>
                    <span>20 Mei 2024 - 26 Mei 2024</span>
                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                </div>
                
                <button class="relative text-gray-400 hover:text-lacera">
                    <i class="far fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-lacera text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center rounded-full border-2 border-white">3</span>
                </button>
                <button class="text-gray-400 hover:text-lacera">
                    <i class="fas fa-expand text-lg"></i>
                </button>
                
                <div class="flex items-center gap-3 border-l border-gray-100 pl-6">
                    <div class="w-10 h-10 rounded-full bg-lacera text-white flex items-center justify-center font-bold text-lg">
                        A
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-gray-800 leading-none">Admin Lacera</p>
                        <p class="text-xs text-gray-500">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-[#f8f9fa] p-8">
            @yield('content')
        </div>

    </main>

    @stack('scripts')
</body>
</html>