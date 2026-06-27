<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Daftar semua pesanan
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Order::with('user')->latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(15);

        // Hitung per-status untuk badge filter
        $counts = [
            'all'        => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'paid'       => Order::where('status', 'paid')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped'    => Order::where('status', 'shipped')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::whereIn('status', ['failed', 'expired', 'cancelled'])->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts', 'status'));
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update status pesanan
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,completed,cancelled',
            'shipping_receipt' => 'nullable|string|max:100',
        ]);

        $newStatus = $request->input('status');
        $now = now();

        // Set timestamp sesuai status
        switch ($newStatus) {
            case 'processing':
                $order->processing_at = $now;
                break;
            case 'shipped':
                $order->shipped_at = $now;
                if ($request->filled('shipping_receipt')) {
                    $order->shipping_receipt = $request->input('shipping_receipt');
                }
                // Pastikan processing_at terisi jika belum
                if (!$order->processing_at) {
                    $order->processing_at = $now;
                }
                break;
            case 'delivered':
                $order->delivered_at = $now;
                if (!$order->shipped_at) {
                    $order->shipped_at = $now;
                }
                if (!$order->processing_at) {
                    $order->processing_at = $now;
                }
                break;
            case 'completed':
                $order->completed_at = $now;
                if (!$order->delivered_at) {
                    $order->delivered_at = $now;
                }
                if (!$order->shipped_at) {
                    $order->shipped_at = $now;
                }
                if (!$order->processing_at) {
                    $order->processing_at = $now;
                }
                break;
        }

        $order->status = $newStatus;
        $order->save();

        return redirect()->route('admin.orders.show', $order)
                         ->with('success', 'Status pesanan berhasil diperbarui menjadi ' . ucfirst($newStatus) . '!');
    }
}
