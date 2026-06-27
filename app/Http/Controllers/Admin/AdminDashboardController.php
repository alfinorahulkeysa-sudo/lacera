<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $successStatuses = ['paid', 'processing', 'shipped', 'delivered', 'completed'];

        $total_penjualan = Order::whereIn('status', $successStatuses)->sum('total_price');
        $total_pesanan   = Order::count();
        $total_produk    = Product::count();
        $total_customer  = User::where('role', 'customer')->orWhereNull('role')->count();
        $total_review    = Review::count();

        // Pesanan Terbaru (5 teratas)
        $latestOrders = Order::with('user')->latest()->take(5)->get();

        $data = compact(
            'total_penjualan',
            'total_pesanan',
            'total_produk',
            'total_customer',
            'total_review',
            'latestOrders'
        );

        return view('admin.dashboard', $data);
    }
}