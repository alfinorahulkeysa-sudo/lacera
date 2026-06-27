@extends('layouts.admin')

@section('content')

{{-- Flash Success --}}
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
        <i class="fas fa-check"></i>
    </div>
    <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
</div>
@endif

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}"
           class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-lacera hover:border-lacera transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan</h2>
            <p class="text-sm text-gray-500 mt-0.5">#{{ $order->order_number }}</p>
        </div>
    </div>

    {{-- Current Status Badge --}}
    @php
        $statusConfig = [
            'pending'    => ['label' => 'Menunggu Bayar', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
            'paid'       => ['label' => 'Lunas',          'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
            'processing' => ['label' => 'Diproses',       'bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
            'shipped'    => ['label' => 'Dikirim',        'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'delivered'  => ['label' => 'Tiba',           'bg' => 'bg-green-100',  'text' => 'text-green-700'],
            'completed'  => ['label' => 'Selesai',        'bg' => 'bg-emerald-100','text' => 'text-emerald-700'],
            'failed'     => ['label' => 'Gagal',          'bg' => 'bg-red-100',    'text' => 'text-red-700'],
            'expired'    => ['label' => 'Expired',        'bg' => 'bg-red-100',    'text' => 'text-red-700'],
            'cancelled'  => ['label' => 'Dibatalkan',     'bg' => 'bg-red-100',    'text' => 'text-red-700'],
        ];
        $sc = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
    @endphp
    <span class="px-5 py-2.5 rounded-full text-sm font-bold {{ $sc['bg'] }} {{ $sc['text'] }}">
        Status: {{ $sc['label'] }}
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT COLUMN: Info Pesanan --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Timeline Tracking --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fas fa-route text-lacera"></i> Timeline Pesanan
            </h3>
            <div class="relative">
                <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-gray-100"></div>
                <div class="space-y-5 relative">

                    {{-- Pesanan Dibuat --}}
                    <div class="flex gap-4">
                        <div class="w-8 h-8 rounded-full bg-lacera text-white flex items-center justify-center flex-shrink-0 z-10 shadow-sm">
                            <i class="fas fa-clipboard-list text-xs"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold text-gray-900">Pesanan Dibuat</h5>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    {{-- Pembayaran --}}
                    @php $isPaid = in_array($order->status, ['paid','processing','shipped','delivered','completed']); @endphp
                    <div class="flex gap-4 {{ !$isPaid ? 'opacity-40' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $isPaid ? 'bg-lacera text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center flex-shrink-0 z-10">
                            <i class="fas fa-credit-card text-xs"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold {{ $isPaid ? 'text-gray-900' : 'text-gray-500' }}">Pembayaran Berhasil</h5>
                            @if($isPaid && $order->paid_at)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->paid_at->format('d F Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Diproses --}}
                    @php $isProc = in_array($order->status, ['processing','shipped','delivered','completed']); @endphp
                    <div class="flex gap-4 {{ !$isProc ? 'opacity-40' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $isProc ? 'bg-lacera text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center flex-shrink-0 z-10">
                            <i class="fas fa-box text-xs"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold {{ $isProc ? 'text-gray-900' : 'text-gray-500' }}">Pesanan Diproses</h5>
                            @if($isProc && $order->processing_at)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->processing_at->format('d F Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Dikirim --}}
                    @php $isShip = in_array($order->status, ['shipped','delivered','completed']); @endphp
                    <div class="flex gap-4 {{ !$isShip ? 'opacity-40' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $isShip ? 'bg-lacera text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center flex-shrink-0 z-10">
                            <i class="fas fa-truck text-xs"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold {{ $isShip ? 'text-gray-900' : 'text-gray-500' }}">Sedang Dikirim</h5>
                            @if($isShip && $order->shipped_at)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->shipped_at->format('d F Y, H:i') }}</p>
                            @endif
                            @if($order->shipping_receipt)
                                <p class="text-xs font-bold text-lacera mt-1 bg-lacera-light px-2 py-1 rounded inline-block">
                                    Resi: {{ $order->shipping_receipt }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tiba --}}
                    @php $isDlv = in_array($order->status, ['delivered','completed']); @endphp
                    <div class="flex gap-4 {{ !$isDlv ? 'opacity-40' : '' }}">
                        <div class="w-8 h-8 rounded-full {{ $isDlv ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center flex-shrink-0 z-10">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                        <div>
                            <h5 class="text-sm font-bold {{ $isDlv ? 'text-emerald-700' : 'text-gray-500' }}">Pesanan Tiba</h5>
                            @if($isDlv && $order->delivered_at)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $order->delivered_at->format('d F Y, H:i') }}</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Produk --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-lacera"></i> Produk Dipesan
                </h3>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 px-6 py-4">
                    <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex-shrink-0">
                        @if($item->product && $item->product->image)
                            <img src="{{ Storage::url($item->product->image) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">{{ $item->product_name ?? ($item->product->name ?? 'Produk') }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-bold text-gray-800 text-sm">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-gray-800">Total Pembayaran</span>
                    <span class="text-xl font-extrabold text-lacera">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT COLUMN: Aksi & Info --}}
    <div class="space-y-6">

        {{-- Update Status Card --}}
        @if(!in_array($order->status, ['failed', 'expired', 'cancelled', 'completed']))
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-lacera"></i> Update Status
            </h3>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                @if($order->status === 'paid')
                    {{-- Paid -> Processing --}}
                    <input type="hidden" name="status" value="processing">
                    <p class="text-sm text-gray-600 mb-3">Klik tombol di bawah untuk memproses pesanan ini.</p>
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-box"></i> Proses Pesanan
                    </button>

                @elseif($order->status === 'processing')
                    {{-- Processing -> Shipped --}}
                    <input type="hidden" name="status" value="shipped">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Resi Pengiriman</label>
                        <input type="text" name="shipping_receipt" required
                               placeholder="Contoh: JNE1234567890"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-lacera focus:ring-2 focus:ring-pink-200 transition">
                    </div>
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-truck"></i> Kirim Pesanan
                    </button>

                @elseif($order->status === 'shipped')
                    {{-- Shipped -> Delivered --}}
                    <input type="hidden" name="status" value="delivered">
                    <p class="text-sm text-gray-600 mb-3">Klik tombol di bawah jika pesanan sudah sampai di tujuan.</p>
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Pesanan Tiba
                    </button>

                @elseif($order->status === 'delivered')
                    {{-- Delivered -> Completed --}}
                    <input type="hidden" name="status" value="completed">
                    <p class="text-sm text-gray-600 mb-3">Tandai pesanan ini sebagai selesai.</p>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-check-double"></i> Selesaikan Pesanan
                    </button>
                @endif
            </form>

            {{-- Cancel Option --}}
            @if(in_array($order->status, ['paid', 'processing']))
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="mt-3"
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="w-full bg-white border-2 border-red-200 text-red-600 font-bold py-2.5 rounded-xl hover:bg-red-50 transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i> Batalkan Pesanan
                </button>
            </form>
            @endif
        </div>
        @endif

        {{-- Info Pelanggan --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user text-lacera"></i> Info Pelanggan
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Nama</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $order->nama_lengkap ?? ($order->user->name ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Email</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ $order->email ?? ($order->user->email ?? '-') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Alamat</p>
                    <p class="text-sm text-gray-800 mt-0.5 leading-relaxed">
                        {{ $order->detail_address ?? '-' }}
                        @if($order->kota), {{ $order->kota }}@endif
                        @if($order->provinsi), {{ $order->provinsi }}@endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Info Pembayaran --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-lacera"></i> Info Pembayaran
            </h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Metode</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Transfer')) }}</p>
                </div>
                @if($order->payment_reference)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Referensi</p>
                    <p class="text-sm font-mono text-gray-800 mt-0.5">{{ $order->payment_reference }}</p>
                </div>
                @endif
                @if($order->courier)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Kurir</p>
                    <p class="text-sm font-semibold text-gray-800 mt-0.5">{{ strtoupper($order->courier) }}</p>
                </div>
                @endif
                @if($order->shipping_receipt)
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold">No. Resi</p>
                    <p class="text-sm font-bold text-lacera mt-0.5 bg-lacera-light px-3 py-1.5 rounded-lg inline-block">{{ $order->shipping_receipt }}</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
