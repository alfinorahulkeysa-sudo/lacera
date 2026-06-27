@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kelola Pesanan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola semua pesanan pelanggan Lacera</p>
    </div>
</div>

{{-- Filter Tabs --}}
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $tabs = [
            'all'        => ['label' => 'Semua',       'icon' => 'fa-list',         'color' => 'gray'],
            'pending'    => ['label' => 'Pending',     'icon' => 'fa-clock',        'color' => 'yellow'],
            'paid'       => ['label' => 'Lunas',       'icon' => 'fa-check-circle', 'color' => 'blue'],
            'processing' => ['label' => 'Diproses',    'icon' => 'fa-box',          'color' => 'orange'],
            'shipped'    => ['label' => 'Dikirim',     'icon' => 'fa-truck',        'color' => 'purple'],
            'delivered'  => ['label' => 'Tiba',        'icon' => 'fa-map-marker-alt','color' => 'green'],
            'completed'  => ['label' => 'Selesai',     'icon' => 'fa-check-double', 'color' => 'emerald'],
            'cancelled'  => ['label' => 'Batal/Gagal', 'icon' => 'fa-times-circle', 'color' => 'red'],
        ];
        $currentTab = $status ?? 'all';
    @endphp
    @foreach($tabs as $key => $tab)
        <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border transition
                  {{ $currentTab === $key
                      ? 'bg-lacera text-white border-lacera shadow-md shadow-pink-200'
                      : 'bg-white text-gray-600 border-gray-200 hover:border-lacera hover:text-lacera' }}">
            <i class="fas {{ $tab['icon'] }}"></i>
            {{ $tab['label'] }}
            @if($counts[$key] > 0)
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full
                    {{ $currentTab === $key ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-600' }}">
                    {{ $counts[$key] }}
                </span>
            @endif
        </a>
    @endforeach
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">No. Pesanan</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800">#{{ $order->order_number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $order->nama_lengkap ?? ($order->user->name ?? '-') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->email ?? ($order->user->email ?? '') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-800">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusConfig = [
                                'pending'    => ['label' => 'Menunggu Bayar', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'dot' => 'bg-yellow-500'],
                                'paid'       => ['label' => 'Lunas',          'bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'dot' => 'bg-blue-500'],
                                'processing' => ['label' => 'Diproses',       'bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
                                'shipped'    => ['label' => 'Dikirim',        'bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'dot' => 'bg-purple-500'],
                                'delivered'  => ['label' => 'Tiba',           'bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500'],
                                'completed'  => ['label' => 'Selesai',        'bg' => 'bg-emerald-100','text' => 'text-emerald-700','dot' => 'bg-emerald-500'],
                                'failed'     => ['label' => 'Gagal',          'bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500'],
                                'expired'    => ['label' => 'Expired',        'bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500'],
                                'cancelled'  => ['label' => 'Dibatalkan',     'bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500'],
                            ];
                            $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'dot' => 'bg-gray-500'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
                            <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                            {{ $sc['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-lacera-light text-lacera rounded-lg text-xs font-bold hover:bg-lacera hover:text-white transition">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                            <i class="fas fa-shopping-bag text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-semibold">Belum ada pesanan</p>
                        <p class="text-gray-400 text-xs mt-1">Pesanan dari pelanggan akan muncul di sini</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $orders->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
