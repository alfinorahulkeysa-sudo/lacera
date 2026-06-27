@extends('layouts.admin')

@section('content')

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau performa penjualan dan keuangan Lacera</p>
    </div>
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-semibold hover:border-lacera hover:text-lacera transition shadow-sm">
        <i class="fas fa-print"></i> Cetak Laporan
    </button>
</div>

{{-- Top Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-pink-50 text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-wallet"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Pendapatan</p>
            <h3 class="text-xl font-bold text-gray-800">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Transaksi Sukses</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $totalOrders }} Transaksi</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Rata-rata Nilai Belanja</p>
            <h3 class="text-xl font-bold text-gray-800">Rp{{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

{{-- Sales Chart --}}
<div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
    <h3 class="font-bold text-gray-800 text-sm mb-6">Tren Penjualan Bulanan ({{ date('Y') }})</h3>
    <div class="h-80 w-full">
        <canvas id="yearlyReportChart"></canvas>
    </div>
</div>

{{-- Performance Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 text-sm">Riwayat Kinerja Bulanan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Bulan</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Jumlah Transaksi</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Rata-rata Transaksi</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @php
                    $monthNames = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                @endphp
                @forelse($monthlySummary as $summary)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ $monthNames[$summary->month] ?? 'Bulan' }} {{ $summary->year }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $summary->count }} Transaksi
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        Rp{{ number_format($summary->revenue / $summary->count, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800">
                        Rp{{ number_format($summary->revenue, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        Belum ada riwayat penjualan terdokumentasi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('yearlyReportChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: {!! json_encode($chartRevenue) !!},
                    backgroundColor: '#e81c62',
                    borderRadius: 8,
                    yAxisID: 'y'
                }, {
                    label: 'Jumlah Transaksi',
                    data: {!! json_encode($chartCount) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: '#3b82f6',
                    type: 'line',
                    tension: 0.4,
                    borderWidth: 2,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    });
</script>
@endpush
