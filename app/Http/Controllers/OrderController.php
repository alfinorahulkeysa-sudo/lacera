<?php

// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Daftar SEMUA pesanan milik customer yang sedang login.
     * Termasuk yang berstatus 'pending' (menunggu konfirmasi pembayaran).
     */
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail satu pesanan.
     */
    public function show($id)
    {
        $order = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }

    /**
     * Cetak bukti pembayaran / invoice.
     * Nama method: printInvoice (sesuai web.php Anda)
     */
    public function printInvoice($id)
    {
        $order = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.print', compact('order'));
    }
}
