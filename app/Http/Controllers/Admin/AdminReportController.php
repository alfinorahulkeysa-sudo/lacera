<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * Tampilkan halaman Laporan Penjualan
     */
    public function index()
    {
        // Status pesanan yang terhitung sukses
        $successStatuses = ['paid', 'processing', 'shipped', 'delivered', 'completed'];

        // 1. Ringkasan Utama
        $totalRevenue = Order::whereIn('status', $successStatuses)->sum('total_price');
        $totalOrders = Order::whereIn('status', $successStatuses)->count();
        $averageOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        // 2. Data Grafik Penjualan Bulanan (Tahun Ini)
        $monthlySalesData = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(id) as count')
            )
            ->whereIn('status', $successStatuses)
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Siapkan array isi data untuk 12 bulan
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartRevenue = array_fill(0, 12, 0);
        $chartCount = array_fill(0, 12, 0);

        foreach ($monthlySalesData as $data) {
            $chartRevenue[$data->month - 1] = (int)$data->revenue;
            $chartCount[$data->month - 1] = (int)$data->count;
        }

        // 3. Ringkasan Kinerja Bulanan (Tabel rangkuman)
        $monthlySummary = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue'),
                DB::raw('COUNT(id) as count')
            )
            ->whereIn('status', $successStatuses)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'months',
            'chartRevenue',
            'chartCount',
            'monthlySummary'
        ));
    }
}
