@extends('layouts.admin')

@section('content')

<!-- TOP CARDS (5 Kolom) -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Penjualan</p>
            <h3 class="text-lg font-bold text-gray-800">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-emerald-500 font-medium mt-1"><i class="fas fa-arrow-up mr-1"></i>28.5% <span class="text-gray-400">dari minggu lalu</span></p>
        </div>
    </div>
    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
            <h3 class="text-lg font-bold text-gray-800">{{ $total_pesanan }}</h3>
            <p class="text-[10px] text-emerald-500 font-medium mt-1"><i class="fas fa-arrow-up mr-1"></i>18.2% <span class="text-gray-400">dari minggu lalu</span></p>
        </div>
    </div>
    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-box"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Produk</p>
            <h3 class="text-lg font-bold text-gray-800">{{ $total_produk }}</h3>
            <p class="text-[10px] text-emerald-500 font-medium mt-1"><i class="fas fa-arrow-up mr-1"></i>5.6% <span class="text-gray-400">dari minggu lalu</span></p>
        </div>
    </div>
    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Customer</p>
            <h3 class="text-lg font-bold text-gray-800">{{ number_format($total_customer, 0, ',', '.') }}</h3>
            <p class="text-[10px] text-emerald-500 font-medium mt-1"><i class="fas fa-arrow-up mr-1"></i>15.3% <span class="text-gray-400">dari minggu lalu</span></p>
        </div>
    </div>
    <!-- Card 5 -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-star"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Review</p>
            <h3 class="text-lg font-bold text-gray-800">{{ $total_review }}</h3>
            <p class="text-[10px] text-emerald-500 font-medium mt-1"><i class="fas fa-arrow-up mr-1"></i>22.1% <span class="text-gray-400">dari minggu lalu</span></p>
        </div>
    </div>
</div>

<!-- BAGIAN CHARTS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Chart Area -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 text-sm">Grafik Penjualan</h3>
            <select class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none">
                <option>Penjualan (Rp)</option>
            </select>
        </div>
        <div class="h-64 w-full">
            <canvas id="salesLineChart"></canvas>
        </div>
    </div>

    <!-- Chart Bar -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800 text-sm">Penjualan Bulanan</h3>
            <select class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none">
                <option>2024</option>
            </select>
        </div>
        <div class="h-64 w-full">
            <canvas id="salesBarChart"></canvas>
        </div>
    </div>
</div>

<!-- BOTTOM SECTIONS (Pesanan, Produk Laris, Statistik) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    
    <!-- Pesanan Terbaru -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-gray-800 text-sm">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-lacera font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($latestOrders as $order)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-50 rounded-lg flex items-center justify-center text-lacera font-bold text-xs shrink-0">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div>
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-[11px] font-bold text-gray-800 hover:text-lacera transition">#{{ $order->order_number }}</a>
                        <p class="text-[10px] text-gray-500">{{ $order->nama_lengkap ?? ($order->user->name ?? 'Customer') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[11px] font-bold text-gray-800 mb-1">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    @php
                        $statusBadges = [
                            'pending'    => 'bg-yellow-50 text-yellow-600',
                            'paid'       => 'bg-blue-50 text-blue-600',
                            'processing' => 'bg-orange-50 text-orange-600',
                            'shipped'    => 'bg-purple-50 text-purple-600',
                            'delivered'  => 'bg-green-50 text-green-600',
                            'completed'  => 'bg-emerald-50 text-emerald-600',
                        ];
                        $badgeClass = $statusBadges[$order->status] ?? 'bg-gray-50 text-gray-600';
                    @endphp
                    <span class="text-[9px] {{ $badgeClass }} font-medium px-2 py-0.5 rounded-full">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-6 text-gray-400 text-xs">Belum ada pesanan terbaru.</div>
            @endforelse
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-gray-800 text-sm">Produk Terlaris</h3>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-lacera font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            <!-- Item -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-lacera font-bold text-sm bg-lacera-light w-5 h-5 rounded-full flex items-center justify-center">1</span>
                    <div class="w-10 h-10 bg-gray-50 rounded-lg p-1">
                         <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=100&q=80" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-800">Glow & Scent Body Lotion</p>
                        <p class="text-[10px] text-gray-500">Terjual 1.248</p>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-gray-800">Rp43.999</p>
            </div>
            <!-- Item -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-lacera font-bold text-sm bg-lacera-light w-5 h-5 rounded-full flex items-center justify-center">2</span>
                    <div class="w-10 h-10 bg-gray-50 rounded-lg p-1">
                         <img src="https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=100&q=80" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-800">Natural Collagen Soap</p>
                        <p class="text-[10px] text-gray-500">Terjual 987</p>
                    </div>
                </div>
                <p class="text-[11px] font-bold text-gray-800">Rp75.999</p>
            </div>
        </div>
    </div>

    <!-- Statistik Customer -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-800 text-sm mb-5">Statistik Customer</h3>
        <div class="relative h-40 w-full flex justify-center mb-4">
            <canvas id="customerPieChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                <span class="text-lg font-bold text-gray-800 leading-none">2.348</span>
                <span class="text-[9px] text-gray-500">Total</span>
            </div>
        </div>
        <div class="flex justify-center gap-4 text-[10px] text-gray-600 mb-4">
            <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#f472b6]"></span> Member Silver</div>
            <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#fbbf24]"></span> Member Gold</div>
        </div>
        <a href="{{ route('admin.users.index') }}" class="block w-full text-center border border-lacera text-lacera text-xs font-bold py-2 rounded-lg hover:bg-lacera hover:text-white transition">Lihat Semua Customer</a>
    </div>

</div>

<!-- Informasi Sistem Bottom Alert -->
<div class="bg-lacera-light border border-pink-200 rounded-xl p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-lacera text-white flex items-center justify-center shadow-sm">
            <i class="fas fa-bell text-xs"></i>
        </div>
        <div>
            <h4 class="font-bold text-sm text-gray-800">Informasi Sistem</h4>
            <p class="text-xs text-gray-600">Ada 12 pesanan baru yang menunggu konfirmasi pembayaran.</p>
        </div>
    </div>
    <a href="#" class="text-sm text-lacera font-bold hover:underline">Lihat Pesanan</a>
</div>

@endsection

@stack('scripts')
@push('scripts')
<script>
    // Konfigurasi Warna
    const laceraPink = '#e81c62';
    const laceraPinkLight = 'rgba(232, 28, 98, 0.1)';
    const laceraPinkLighter = '#fde8ef';

    // 1. GRAFIK PENJUALAN (Line Chart)
    const ctxLine = document.getElementById('salesLineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['20 Mei', '21 Mei', '22 Mei', '23 Mei', '24 Mei', '25 Mei', '26 Mei'],
            datasets: [{
                label: 'Penjualan (Rp)',
                data: [25400000, 32800000, 28600000, 45700000, 38900000, 55600000, 58600000],
                borderColor: laceraPink,
                backgroundColor: laceraPinkLight,
                borderWidth: 2,
                pointBackgroundColor: laceraPink,
                pointBorderColor: '#fff',
                pointRadius: 4,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { callback: function(value) { return value / 1000000 + 'Jt'; } } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. PENJUALAN BULANAN (Bar Chart)
    const ctxBar = document.getElementById('salesBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Penjualan',
                data: [120, 150, 180, 210, 245, 190, 175, 200, 220, 230, 215, 250],
                backgroundColor: function(context) {
                    return context.dataIndex === 4 ? laceraPink : '#f9a8d4'; // Highlight bulan Mei
                },
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4] }, ticks: { callback: function(value) { return value + 'Jt'; } } },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. STATISTIK CUSTOMER (Doughnut Chart)
    const ctxPie = document.getElementById('customerPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Member Silver', 'Member Gold', 'Member Platinum'],
            datasets: [{
                data: [53, 36, 11],
                backgroundColor: ['#f472b6', '#fbbf24', '#8b5cf6'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
@endpush