{{-- resources/views/orders/print.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran #{{ $order->order_number }} - Lacera</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            font-size: 13px;
        }

        .receipt-wrapper {
            max-width: 680px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* ---- HEADER ---- */
        .receipt-header {
            background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%);
            color: #fff;
            padding: 28px 32px 24px;
        }

        .brand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .brand-tagline {
            font-size: 11px;
            opacity: 0.75;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .paid-stamp {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.5);
            border-radius: 8px;
            padding: 6px 14px;
            text-align: center;
        }

        .paid-stamp .stamp-label { font-size: 9px; letter-spacing: 1.5px; opacity: 0.8; }
        .paid-stamp .stamp-value { font-size: 14px; font-weight: 800; }

        .order-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .meta-item .meta-label {
            font-size: 10px;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .meta-item .meta-value {
            font-size: 12px;
            font-weight: 700;
            margin-top: 3px;
        }

        /* ---- CUSTOMER INFO ---- */
        .section {
            padding: 20px 32px;
            border-bottom: 1px solid #f0f0f0;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #e91e63;
            margin-bottom: 10px;
        }

        .customer-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #fce4ec;
            color: #e91e63;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .customer-name { font-weight: 700; font-size: 13px; }
        .customer-email { color: #888; font-size: 11px; margin-top: 1px; }

        /* ---- ITEMS TABLE ---- */
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead tr {
            background: #fce4ec;
        }

        .items-table th {
            padding: 10px 32px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #c2185b;
        }

        .items-table th:last-child { text-align: right; }

        .items-table td {
            padding: 14px 32px;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5;
            font-size: 12px;
        }

        .items-table td:last-child { text-align: right; }

        .product-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .product-img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            background: #fce4ec;
            border: 1px solid #f8bbd0;
        }

        .product-img-placeholder {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #fce4ec;
            border: 1px solid #f8bbd0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e91e63;
            font-weight: 700;
            font-size: 14px;
        }

        .product-name { font-weight: 600; font-size: 12px; }
        .product-sku  { font-size: 10px; color: #aaa; margin-top: 2px; }

        /* ---- SUMMARY ---- */
        .summary-section {
            padding: 16px 32px 20px;
            background: #fafafa;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            color: #555;
            font-size: 12px;
        }

        .summary-row.free { color: #22c55e; }
        .summary-row.discount { color: #22c55e; }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 12px;
            margin-top: 8px;
            border-top: 2px solid #e91e63;
        }

        .summary-total .label {
            font-weight: 800;
            font-size: 14px;
            color: #333;
        }

        .summary-total .amount {
            font-weight: 900;
            font-size: 22px;
            color: #e91e63;
        }

        /* ---- FOOTER ---- */
        .receipt-footer {
            padding: 20px 32px;
            background: #fff;
            border-top: 2px dashed #f0f0f0;
            text-align: center;
        }

        .footer-thanks {
            font-size: 15px;
            font-weight: 800;
            color: #e91e63;
            margin-bottom: 4px;
        }

        .footer-sub { font-size: 11px; color: #aaa; }

        .footer-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 14px 0;
        }

        .footer-divider .line { flex: 1; height: 1px; background: #f0f0f0; }
        .footer-divider .dot  { width: 6px; height: 6px; border-radius: 50%; background: #e91e63; }

        .footer-note { font-size: 10px; color: #bbb; }

        /* ---- PRINT BUTTON ---- */
        .print-btn-area {
            max-width: 680px;
            margin: 0 auto 30px;
            display: flex;
            gap: 12px;
            justify-content: center;
            padding: 0 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print { background: #e91e63; color: #fff; }
        .btn-print:hover { background: #c2185b; }
        .btn-back  { background: #fff; color: #555; border: 1.5px solid #ddd; }
        .btn-back:hover { background: #f5f5f5; }

        /* ---- PRINT MEDIA ---- */
        @media print {
            body { background: #fff; }
            .receipt-wrapper {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
            .print-btn-area { display: none; }
        }
    </style>
</head>
<body>

    {{-- Print Button (hidden on print) --}}
    <div class="print-btn-area">
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-back">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <div class="receipt-wrapper">

        {{-- ===== HEADER ===== --}}
        <div class="receipt-header">
            <div class="brand-row">
                <div>
                    <div class="brand-name">LACERA</div>
                    <div class="brand-tagline">Official Store</div>
                </div>
                <div class="paid-stamp">
                    <div class="stamp-label">Status</div>
                    <div class="stamp-value">✓ LUNAS</div>
                </div>
            </div>

            <div class="order-meta">
                <div class="meta-item">
                    <div class="meta-label">No. Pesanan</div>
                    <div class="meta-value">#{{ $order->order_number }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Tanggal Bayar</div>
                    <div class="meta-value">
                        {{ \Carbon\Carbon::parse($order->paid_at ?? $order->created_at)
                            ->translatedFormat('d M Y') }}
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Metode Bayar</div>
                    <div class="meta-value">{{ $order->payment_method ?? 'Transfer Bank' }}</div>
                </div>
            </div>
        </div>

        {{-- ===== CUSTOMER ===== --}}
        <div class="section">
            <div class="section-title">Informasi Pembeli</div>
            <div class="customer-row">
                <div class="customer-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="customer-name">{{ Auth::user()->name }}</div>
                    <div class="customer-email">{{ Auth::user()->email }}</div>
                </div>
            </div>
        </div>

        {{-- ===== PRODUCT TABLE ===== --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="product-row">
                            @if($item->product && $item->product->image)
                                <img src="{{ Storage::url($item->product->image) }}"
                                     alt="{{ $item->product_name }}"
                                     class="product-img">
                            @else
                                <div class="product-img-placeholder">
                                    {{ strtoupper(substr($item->product_name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="product-name">{{ $item->product_name ?? $item->product->name ?? 'Produk' }}</div>
                                @if($item->product && $item->product->sku)
                                <div class="product-sku">SKU: {{ $item->product->sku }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $item->quantity }}×</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ===== PRICE SUMMARY ===== --}}
        <div class="summary-section">
            <div class="summary-row">
                <span>Subtotal ({{ $order->items->sum('quantity') }} produk)</span>
                <span>Rp{{ number_format($order->subtotal ?? $order->total_price, 0, ',', '.') }}</span>
            </div>

            @if(isset($order->shipping_cost) && $order->shipping_cost > 0)
            <div class="summary-row">
                <span>Ongkos Kirim</span>
                <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @else
            <div class="summary-row free">
                <span>Ongkos Kirim</span>
                <span>Gratis ✓</span>
            </div>
            @endif

            @if(isset($order->discount_amount) && $order->discount_amount > 0)
            <div class="summary-row discount">
                <span>Diskon Promo</span>
                <span>- Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif

            <div class="summary-total">
                <span class="label">Total Pembayaran</span>
                <span class="amount">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="receipt-footer">
            <div class="footer-thanks">Terima kasih sudah berbelanja di Lacera! 🎉</div>
            <div class="footer-sub">Produk kecantikan terpercaya untuk kulit sehat &amp; bercahaya</div>

            <div class="footer-divider">
                <div class="line"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="line"></div>
            </div>

            <div class="footer-note">
                Dokumen ini dicetak pada
                {{ now()->translatedFormat('d F Y, H:i') }} WIB
                &nbsp;·&nbsp; lacera.id
                &nbsp;·&nbsp; Simpan dokumen ini sebagai bukti transaksi Anda
            </div>
        </div>

    </div>

</body>
</html>
