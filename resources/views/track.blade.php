@extends('layouts.app')

@section('title', 'Lacak Pesanan - Lacera')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4">
        
        {{-- Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Lacak Pesanan</h1>
            <p class="text-gray-500 mt-2 text-sm">Masukkan Nomor Pesanan (Order ID) untuk melihat status pesanan kamu.</p>
        </div>

        {{-- Form Lacak --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8">
            <form action="{{ route('track.search') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="reference" class="block text-sm font-bold text-gray-700 mb-2">Nomor Pesanan (Order ID)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="reference" id="reference" 
                               value="{{ old('reference', $reference ?? '') }}" required
                               placeholder="Contoh: LACERA-178255..."
                               class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition-all font-medium">
                    </div>
                </div>
                <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-pink-200 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    Lacak Sekarang
                </button>
            </form>
        </div>

        {{-- Hasil Pencarian --}}
        @if(isset($order))
            @if($order)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-50 to-white px-8 py-6 border-b border-pink-100 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs text-pink-600 font-bold uppercase tracking-wider mb-1">Status Pesanan</p>
                            <h3 class="text-xl font-black text-gray-900">#{{ $order->order_number }}</h3>
                        </div>
                        
                        {{-- Status Badge --}}
                        @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered', 'completed']))
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                                Pesanan Berhasil / Lunas
                            </span>
                        @elseif($order->status === 'pending')
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                Menunggu Pembayaran
                            </span>
                        @elseif(in_array($order->status, ['failed', 'expired', 'cancelled']))
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                Gagal / Dibatalkan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                {{ ucfirst($order->status) }}
                            </span>
                        @endif
                    </div>

                    <div class="p-8">
                        {{-- TIMELINE PELACAKAN PESANAN --}}
                        <div class="mb-10">
                            <h4 class="font-bold text-gray-900 mb-6 text-lg">Riwayat Perjalanan Pesanan</h4>
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal Pesan</p>
                                <p class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Metode Pembayaran</p>
                                <p class="font-semibold text-gray-900">
                                    {{ str_replace('_', ' ', $order->payment_method ?? 'Transfer') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Penerima</p>
                                <p class="font-semibold text-gray-900">{{ $order->nama_lengkap ?? $order->user->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Total Pembayaran</p>
                                <p class="font-bold text-pink-600 text-lg">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-8 border-t border-gray-100">
                            <h4 class="font-bold text-gray-900 mb-4">Detail Produk</h4>
                            <div class="space-y-4">
                                @foreach($order->items as $item)
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover" alt="Product">
                                        @else
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        @if($order->status === 'pending' && $order->payment_url)
                        <div class="mt-8 pt-6">
                            <a href="{{ $order->payment_url }}" target="_blank" class="block w-full text-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3.5 rounded-2xl transition shadow-lg shadow-yellow-200">
                                Lanjutkan Pembayaran
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            @else
                <div class="bg-red-50 border border-red-100 rounded-3xl p-8 text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-red-800 mb-2">Pesanan Tidak Ditemukan</h3>
                    <p class="text-red-600 text-sm">Kami tidak dapat menemukan pesanan dengan nomor referensi <strong>"{{ $reference }}"</strong>. Silakan periksa kembali nomor pesanan Anda.</p>
                </div>
            @endif
        @endif
        
    </div>
</div>
@endsection
