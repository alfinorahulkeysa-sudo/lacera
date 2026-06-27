<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan mengirimkan data dasar yang dibutuhkan
     */
    public function index()
    {
        $user = auth()->user();
        
        // Mengambil total harga produk dari session (default Rp150.000 jika kosong)
        $totalHarga = session('cart_total', 150000); 
        $totalBerat = session('cart_weight', 1000);

        // Provide a minimal local provinces list so the view doesn't break.
        // This is a fallback for environments where external RajaOngkir API
        // is unavailable. Replace with real API/db lookup as needed.
        $provinces = [
            ['province_id' => '31', 'province' => 'DKI Jakarta'],
            ['province_id' => '32', 'province' => 'Jawa Barat'],
            ['province_id' => '33', 'province' => 'Jawa Tengah'],
            ['province_id' => '34', 'province' => 'DI Yogyakarta'],
            ['province_id' => '35', 'province' => 'Jawa Timur'],
            ['province_id' => '36', 'province' => 'Banten'],
        ];

        return view('checkout', compact('user', 'totalHarga', 'totalBerat', 'provinces'));
    }

    /**
     * Return a small sample list of cities for a given province id.
     * Frontend expects JSON array of cities with `city_id`, `city_name`, and `type`.
     */
    public function getCities($province_id)
    {
        $samples = [
            '31' => [
                ['city_id' => '3171', 'city_name' => 'Kota Jakarta Selatan', 'type' => 'Kota'],
                ['city_id' => '3172', 'city_name' => 'Kota Jakarta Timur', 'type' => 'Kota'],
            ],
            '32' => [
                ['city_id' => '3273', 'city_name' => 'Kota Bandung', 'type' => 'Kota'],
                ['city_id' => '3274', 'city_name' => 'Kabupaten Bandung', 'type' => 'Kabupaten'],
            ],
            '33' => [
                ['city_id' => '3375', 'city_name' => 'Kota Semarang', 'type' => 'Kota'],
                ['city_id' => '3376', 'city_name' => 'Kabupaten Semarang', 'type' => 'Kabupaten'],
            ],
        ];

        return response()->json($samples[$province_id] ?? []);
    }

    /**
     * Return a mock shipping-cost structure compatible with the frontend.
     */
    public function getShippingCost(Request $request)
    {
        $data = $request->validate([
            'city_id' => 'required|string',
            'weight'  => 'required|numeric',
            'courier' => 'required|string',
        ]);

        // Simple mocked costs — replace with real courier API calls when available.
        $base = 10000; // base cost
        $perKg = 5000; // per kg
        $kg = max(1, ceil($data['weight'] / 1000));
        $costValue = $base + ($perKg * $kg);

        $response = [
            [
                'code' => $data['courier'],
                'name' => strtoupper($data['courier']),
                'costs' => [
                    [
                        'service' => 'REG',
                        'cost' => [
                            ['value' => $costValue, 'etd' => '2-3']
                        ]
                    ],
                    [
                        'service' => 'YES',
                        'cost' => [
                            ['value' => $costValue + 15000, 'etd' => '1-1']
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);
    }

    /**
     * Memproses data checkout dan mengirimkan data transaksi ke Gateway Paymenku
     */
    public function processCheckout(Request $request)
    {
        // Validasi disesuaikan untuk pengiriman manual (menggunakan teks biasa, bukan ID API)
        $request->validate([
            'nama_lengkap'   => 'required|string',
            'email'          => 'required|email',
            'province_id'    => 'required|string', // match form field names
            'city_id'        => 'required|string', // match form field names
            'detail_address' => 'required|string',
            'shipping_cost'  => 'required|numeric',   // Ongkir manual yang dikirim dari form/session
            'total_harga'    => 'required|numeric', 
            'payment_method' => 'required|string', 
        ]);

        $orderId = 'LACERA-' . time(); 
        $totalHarga = $request->total_harga; 

        $apiKey = env('PAYMENKU_API_KEY');
        $endpointUrl = 'https://paymenku.com/api/v1/transaction/create'; 

        // Proses payload ke Paymenku tetap dipertahankan karena sudah sukses
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post($endpointUrl, [
            'reference_id'   => $orderId,
            'amount'         => $totalHarga,
            'customer_name'  => $request->nama_lengkap,
            'customer_email' => $request->email,
            'channel_code'   => $request->payment_method, 
            'return_url'     => url('/'),
        ]);

        if ($response->successful()) {
            $result = $response->json();
            $paymentUrl = $result['data']['pay_url'] ?? null;

            if ($paymentUrl) {
                // Simpan order lokal sebelum redirect ke pembayaran
                DB::transaction(function () use ($request, $orderId, $totalHarga, $paymentUrl) {
                    $user = Auth::user();

                    $orderData = [
                        'user_id' => $user->id,
                        'order_number' => $orderId,
                        'total_price' => $totalHarga,
                        'shipping_cost' => $request->shipping_cost,
                        'courier' => $request->payment_method,
                        'status' => 'pending',
                        'proof_of_payment' => null,
                        'shipping_receipt' => null,
                    ];

                    if (Schema::hasColumn('orders', 'nama_lengkap')) {
                        $orderData['nama_lengkap'] = $request->nama_lengkap;
                    }
                    if (Schema::hasColumn('orders', 'email')) {
                        $orderData['email'] = $request->email;
                    }
                    if (Schema::hasColumn('orders', 'provinsi')) {
                        $orderData['provinsi'] = $request->province_id;
                    }
                    if (Schema::hasColumn('orders', 'kota')) {
                        $orderData['kota'] = $request->city_id;
                    }
                    if (Schema::hasColumn('orders', 'detail_address')) {
                        $orderData['detail_address'] = $request->detail_address;
                    }
                    if (Schema::hasColumn('orders', 'payment_method')) {
                        $orderData['payment_method'] = $request->payment_method;
                    }
                    if (Schema::hasColumn('orders', 'payment_reference')) {
                        $orderData['payment_reference'] = $orderId;
                    }
                    if (Schema::hasColumn('orders', 'payment_url')) {
                        $orderData['payment_url'] = $paymentUrl;
                    }

                    $order = Order::create($orderData);

                    $cartItems = CartItem::where('user_id', $user->id)->with('product')->get();
                    foreach ($cartItems as $item) {
                        OrderItem::create([
                            'order_id'     => $order->id,
                            'product_id'   => $item->product_id,
                            'product_name' => $item->product->name ?? 'Produk',
                            'quantity'     => $item->quantity,
                            'price'        => $item->product->price,
                        ]);
                    }
                });

                return redirect()->away($paymentUrl);
            }
        }

        return back()->with('error', 'Gagal memproses pembayaran ke Paymenku. Keterangan: ' . $response->body());
    }

    /**
     * Menangani callback status pembayaran dari Paymenku.
     * Paymenku mengirim POST dengan JSON payload berisi status transaksi.
     */
    public function handleCallback(Request $request)
    {
        // Log payload mentah untuk debugging
        \Log::info('Paymenku Callback Received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        // ── 1. Verifikasi Signature (opsional tapi disarankan) ──
        $webhookSecret = env('PAYMENKU_WEBHOOK_SECRET');
        $signature     = $request->header('X-Signature') ?? $request->header('x-signature');

        if ($webhookSecret && $signature) {
            $rawBody        = file_get_contents('php://input');
            $expectedHash   = hash_hmac('sha256', $rawBody, $webhookSecret);

            if (!hash_equals($expectedHash, $signature)) {
                \Log::warning('Paymenku Callback: signature mismatch', [
                    'expected' => $expectedHash,
                    'received' => $signature,
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }
        }

        // ── 2. Ambil data dari payload ──
        // Paymenku biasanya mengirim reference_id dan status.
        // Kita coba ambil dari nested "data" dulu, lalu dari root payload.
        $data        = $request->input('data', []);
        $referenceId = $data['reference_id'] ?? $request->input('reference_id');
        $status      = $data['status']       ?? $request->input('status');

        if (!$referenceId) {
            \Log::warning('Paymenku Callback: missing reference_id', $request->all());
            return response()->json(['status' => 'error', 'message' => 'Missing reference_id'], 400);
        }

        // ── 3. Cari order berdasarkan order_number (= reference_id saat create) ──
        $order = Order::where('order_number', $referenceId)->first();

        if (!$order) {
            // Coba cari via payment_reference sebagai fallback
            $order = Order::where('payment_reference', $referenceId)->first();
        }

        if (!$order) {
            \Log::warning('Paymenku Callback: order not found', ['reference_id' => $referenceId]);
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        // ── 4. Update status berdasarkan callback ──
        $paymentStatus = strtolower($status ?? '');

        \Log::info('Paymenku Callback: processing', [
            'order_id'       => $order->id,
            'order_number'   => $order->order_number,
            'current_status' => $order->status,
            'payment_status' => $paymentStatus,
        ]);

        // Mapping status Paymenku ke status internal
        if (in_array($paymentStatus, ['paid', 'success', 'succeeded', 'settlement', 'completed'])) {
            $order->status  = 'paid';
            $order->paid_at = now();
            $order->save();

            \Log::info('Paymenku Callback: order marked as paid', ['order_id' => $order->id]);

        } elseif (in_array($paymentStatus, ['expired', 'failed', 'cancelled', 'denied'])) {
            $order->status = 'failed';
            $order->save();

            \Log::info('Paymenku Callback: order marked as failed', ['order_id' => $order->id]);
        }
        // Status lainnya (pending, processing) → tidak mengubah apa-apa

        return response()->json(['status' => 'success']);
    }
}