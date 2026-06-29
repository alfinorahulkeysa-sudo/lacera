{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app') {{-- sesuaikan dengan nama layout customer Anda --}}

@section('title', 'Pesanan Saya - Lacera')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
<div class="max-w-4xl mx-auto px-4">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pesanan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat semua pesanan kamu</p>
    </div>

    {{-- ===== STATS BADGE ===== --}}
    @if($orders->total() > 0)
    <div class="inline-flex items-center gap-2 bg-pink-50 border border-pink-200 text-pink-700
                text-sm font-semibold px-4 py-2 rounded-full mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ $orders->total() }} Pesanan
    </div>
    @endif

    {{-- ===== ORDER LIST ===== --}}
    @forelse($orders as $order)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-5 overflow-hidden">

        {{-- Order Header --}}
        <div class="flex flex-wrap items-center justify-between gap-3 px-6 py-4
                    bg-gradient-to-r from-pink-50 to-white border-b border-pink-100">
            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">No. Pesanan</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">#{{ $order->order_number }}</p>
                </div>
                <div class="w-px h-8 bg-gray-200 hidden sm:block"></div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Tanggal Pesan</p>
                    <p class="text-sm font-medium text-gray-700 mt-0.5">
                        {{ \Carbon\Carbon::parse($order->paid_at ?? $order->created_at)->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
            </div>
            {{-- Status Badge — dinamis berdasarkan status --}}
            @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered', 'completed']))
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                         bg-green-100 text-green-700 border border-green-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                Pembayaran Lunas
            </span>
            @elseif($order->status === 'pending')
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                    bg-green-100 text-green-700 border border-green-200">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
            Pembayaran berhasil
        </span>
            @elseif(in_array($order->status, ['failed', 'expired', 'cancelled']))
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                         bg-red-100 text-red-700 border border-red-200">
                <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                Gagal / Dibatalkan
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                         bg-gray-100 text-gray-700 border border-gray-200">
                {{ ucfirst($order->status) }}
            </span>
            @endif
        </div>

        {{-- Product Items --}}
        <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 px-6 py-4">
                {{-- Thumbnail --}}
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-pink-50 border border-gray-100">
                    @if($item->product && $item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}"
                             alt="{{ $item->product_name ?? $item->product->name ?? 'Produk' }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-pink-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586
                                         a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6
                                         a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Rp{{ number_format($item->price, 0, ',', '.') }}
                        × {{ $item->quantity }}
                    </p>
                </div>

                {{-- Subtotal --}}
                <p class="font-bold text-gray-800 text-sm flex-shrink-0">
                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- Order Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-4 px-6 py-4
                    border-t border-gray-100 bg-gray-50/60">
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Pembayaran</p>
                <p class="text-xl font-extrabold text-pink-600 mt-0.5">
                    Rp{{ number_format($order->total_price, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Lihat Detail --}}
                <a href="{{ route('orders.show', $order->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200
                          rounded-xl text-sm text-gray-600 hover:bg-white hover:border-gray-300
                          transition font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Detail
                </a>
                {{-- Cetak Bukti (hanya jika sudah bayar) --}}
                @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered', 'completed']))
                <a href="{{ route('orders.print', $order->id) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-pink-600 text-white
                          rounded-xl text-sm font-semibold hover:bg-pink-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4
                                 a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9
                                 a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9
                                 a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Bukti
                </a>
                @elseif($order->status === 'pending' && $order->payment_url)
                <a href="{{ $order->payment_url }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white
                        rounded-xl text-sm font-semibold hover:bg-green-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0
                                002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2v6a2 2 0 002 2z"/>
                    </svg>
                    Lihat Pembayaran
                </a>
                @endif
            </div>
        </div>

    </div>
    @empty
    {{-- Empty State --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-20 text-center">
        <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center
                    mx-auto mb-5 text-pink-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-700 mb-2">Belum ada pesanan</h3>
        <p class="text-sm text-gray-400 mb-6">Kamu belum memiliki transaksi apapun.</p>
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-pink-600 text-white
                  rounded-xl text-sm font-semibold hover:bg-pink-700 transition">
            Mulai Belanja
        </a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif

</div>
</div>
@endsection
