{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
<div class="max-w-3xl mx-auto px-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-pink-500 transition">Beranda</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('orders.index') }}" class="hover:text-pink-500 transition">Pesanan Saya</a>
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-700 font-medium">{{ $order->order_number }}</span>
    </nav>

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="bg-gradient-to-r from-pink-600 to-rose-500 px-6 py-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-pink-200 text-xs font-semibold uppercase tracking-wide">Bukti Pembelian</p>
                    <h2 class="text-white text-xl font-bold mt-1">#{{ $order->order_number }}</h2>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/20 backdrop-blur
                             rounded-full text-xs font-bold text-white border border-white/30">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0
                                 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414
                                 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Pembayaran Lunas
                </span>
            </div>

            {{-- Meta Info --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5">
                <div>
                    <p class="text-pink-200 text-xs">Tanggal Pembayaran</p>
                    <p class="text-white text-sm font-semibold mt-0.5">
                        {{ \Carbon\Carbon::parse($order->paid_at ?? $order->created_at)
                            ->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-pink-200 text-xs">Jam</p>
                    <p class="text-white text-sm font-semibold mt-0.5">
                        {{ \Carbon\Carbon::parse($order->paid_at ?? $order->created_at)->format('H:i') }} WIB
                    </p>
                </div>
                <div>
                    <p class="text-pink-200 text-xs">Metode Bayar</p>
                    <p class="text-white text-sm font-semibold mt-0.5">
                        {{ $order->payment_method ?? 'Transfer Bank' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Informasi Pembeli</p>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-pink-100 flex items-center justify-center
                            text-pink-600 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Status Perjalanan Pesanan</h3>
        </div>
        <div class="p-6">
            <div class="relative">
                {{-- Garis Vertikal --}}
                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-100"></div>

                <div class="space-y-6 relative">
                    {{-- Tahap 1: Pesanan Dibuat --}}
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-pink-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm shadow-pink-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold text-gray-900">Pesanan Dibuat</h5>
                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    @if(in_array($order->status, ['failed', 'expired', 'cancelled']))
                        {{-- Jika Gagal / Batal --}}
                        <div class="flex gap-4">
                            <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm shadow-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-red-600">Pesanan Dibatalkan / Gagal</h5>
                                <p class="text-xs text-gray-500 mt-1">Sistem membatalkan pesanan karena melewati batas waktu pembayaran atau ditolak.</p>
                            </div>
                        </div>
                    @else

                        {{-- Tahap 2: Pembayaran --}}
                        @php $isPaid = in_array($order->status, ['paid', 'processing', 'shipped', 'delivered', 'completed']); @endphp
                        <div class="flex gap-4 opacity-{{ $isPaid ? '100' : '40' }}">
                            <div class="w-8 h-8 rounded-full {{ $isPaid ? 'bg-pink-500 text-white shadow-sm shadow-pink-200' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center flex-shrink-0 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold {{ $isPaid ? 'text-gray-900' : 'text-gray-600' }}">Pembayaran Berhasil</h5>
                                @if($isPaid && $order->paid_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->paid_at)->translatedFormat('d F Y, H:i') }}</p>
                                @elseif(!$isPaid)
                                    <p class="text-xs text-gray-400 mt-1">Menunggu konfirmasi pembayaran</p>
                                @endif
                            </div>
                        </div>

                        {{-- Tahap 3: Diproses --}}
                        @php $isProcessing = in_array($order->status, ['processing', 'shipped', 'delivered', 'completed']); @endphp
                        <div class="flex gap-4 opacity-{{ $isProcessing ? '100' : '40' }}">
                            <div class="w-8 h-8 rounded-full {{ $isProcessing ? 'bg-pink-500 text-white shadow-sm shadow-pink-200' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center flex-shrink-0 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold {{ $isProcessing ? 'text-gray-900' : 'text-gray-600' }}">Pesanan Diproses Penjual</h5>
                                @if($isProcessing && $order->processing_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->processing_at)->translatedFormat('d F Y, H:i') }}</p>
                                @elseif(!$isProcessing && $isPaid)
                                    <p class="text-xs text-gray-400 mt-1">Penjual sedang menyiapkan pesananmu</p>
                                @endif
                            </div>
                        </div>

                        {{-- Tahap 4: Dikirim --}}
                        @php $isShipped = in_array($order->status, ['shipped', 'delivered', 'completed']); @endphp
                        <div class="flex gap-4 opacity-{{ $isShipped ? '100' : '40' }}">
                            <div class="w-8 h-8 rounded-full {{ $isShipped ? 'bg-pink-500 text-white shadow-sm shadow-pink-200' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center flex-shrink-0 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold {{ $isShipped ? 'text-gray-900' : 'text-gray-600' }}">Sedang Dikirim</h5>
                                @if($isShipped && $order->shipped_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->shipped_at)->translatedFormat('d F Y, H:i') }}</p>
                                @endif
                                @if($isShipped && $order->shipping_receipt)
                                    <p class="text-xs font-semibold text-pink-600 mt-1 bg-pink-50 px-2 py-1 rounded inline-block">Resi: {{ $order->shipping_receipt }}</p>
                                @endif
                            </div>
                        </div>

                        {{-- Tahap 5: Selesai / Tiba --}}
                        @php $isDelivered = in_array($order->status, ['delivered', 'completed']); @endphp
                        <div class="flex gap-4 opacity-{{ $isDelivered ? '100' : '40' }}">
                            <div class="w-8 h-8 rounded-full {{ $isDelivered ? 'bg-green-500 text-white shadow-sm shadow-green-200' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center flex-shrink-0 z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold {{ $isDelivered ? 'text-green-700' : 'text-gray-600' }}">Pesanan Tiba di Tujuan</h5>
                                @if($isDelivered && $order->delivered_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($order->delivered_at)->translatedFormat('d F Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Product Items Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Produk yang Dibeli</h3>
        </div>

        <div class="divide-y divide-gray-50">
            @foreach($order->items as $item)
            <div class="flex items-center gap-4 px-6 py-5">
                {{-- Thumbnail --}}
                <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 bg-pink-50 border border-gray-100">
                    @if($item->product && $item->product->image)
                        <img src="{{ Storage::url($item->product->image) }}"
                             alt="{{ $item->product_name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-pink-200">
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
                    <p class="font-semibold text-gray-800 text-sm">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                    @if($item->product && $item->product->sku)
                    <p class="text-xs text-gray-400 mt-0.5 font-mono">SKU: {{ $item->product->sku }}</p>
                    @endif
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {{ $item->quantity }} pcs
                        </span>
                        <span class="text-xs text-gray-500">
                            @ Rp{{ number_format($item->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Subtotal --}}
                <div class="text-right flex-shrink-0">
                    <p class="font-bold text-gray-800 text-sm">
                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Price Summary --}}
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            <div class="space-y-2 max-w-xs ml-auto">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Subtotal ({{ $order->items->sum('quantity') }} produk)</span>
                    <span>Rp{{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}</span>
                </div>
                @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @else
                <div class="flex justify-between text-sm text-green-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-semibold">Gratis</span>
                </div>
                @endif
                @if(isset($order->discount_amount) && $order->discount_amount > 0)
                <div class="flex justify-between text-sm text-green-600">
                    <span>Diskon Promo</span>
                    <span>- Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total Pembayaran</span>
                    <span class="text-xl font-extrabold text-pink-600">
                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('orders.index') }}"
           class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 border border-gray-200
                  bg-white rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Pesanan
        </a>
        <a href="{{ route('orders.print', $order->id) }}" target="_blank"
           class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 bg-pink-600 text-white
                  rounded-xl text-sm font-bold hover:bg-pink-700 transition shadow-md shadow-pink-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4
                         a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9
                         a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9
                         a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Bukti Pembayaran
        </a>
    </div>

</div>
</div>
@endsection
