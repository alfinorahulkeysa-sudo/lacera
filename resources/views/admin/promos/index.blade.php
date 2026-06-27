@extends('layouts.admin') 

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Kelola Promo</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola semua program diskon dan promo toko Lacera</p>
    </div>
    <div>
        <a href="{{ route('admin.promos.create') }}" class="inline-flex items-center justify-center bg-pink-600 hover:bg-pink-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Promo
        </a>
    </div>
</div>

{{-- Alert Notifikasi --}}
@if(session('success'))
<div class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2">
    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
    {{-- Form Filter Pencarian --}}
    <div class="p-4 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row gap-3">
        <form action="{{ route('admin.promos.index') }}" method="GET" class="flex flex-1 gap-3">
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau tipe promo..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-pink-500 focus:border-pink-500">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                Cari
            </button>
            <a href="{{ route('admin.promos.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                Reset
            </a>
        </form>
    </div>

    {{-- Tabel Data Promo --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-sm text-gray-600 font-semibold">
                    <th class="py-4 px-6 text-center w-16">No</th>
                    <th class="py-4 px-6">Nama Promo</th>
                    <th class="py-4 px-6">Tipe Promo</th>
                    <th class="py-4 px-6 text-center">Nilai</th>
                    <th class="py-4 px-6 text-center">Tanggal Mulai</th>
                    <th class="py-4 px-6 text-center">Tanggal Selesai</th>
                    <th class="py-4 px-6 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($promos as $index => $promo)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-gray-700 text-center font-medium">{{ $promos->firstItem() + $index }}</td>
                    <td class="py-4 px-6 font-semibold text-gray-900">{{ $promo->name }}</td>
                    <td class="py-4 px-6 text-gray-600">
                        <span class="bg-gray-100 text-gray-800 text-xs px-2.5 py-1 rounded-md font-medium capitalize">
                            {{ $promo->type }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="bg-pink-100 text-pink-700 text-xs px-2.5 py-1 rounded-md font-bold">
                            {{ number_format($promo->value, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center text-gray-600">
                        {{ date('d M Y H:i', strtotime($promo->start_date)) }}
                    </td>
                    <td class="py-4 px-6 text-center text-gray-600">
                        {{ date('d M Y H:i', strtotime($promo->end_date)) }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.promos.edit', $promo) }}" class="p-2 text-pink-600 bg-pink-50 border border-pink-200 rounded-lg hover:bg-pink-100 transition-colors" title="Edit Promo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </a>
                            
                            {{-- Tombol Hapus --}}
                            <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors" title="Hapus Promo">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-500 font-medium">
                        Tidak ada data promo ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Pagination --}}
    @if($promos->hasPages())
    <div class="p-4 border-t border-gray-200 bg-white">
        {{ $promos->links() }}
    </div>
    @endif
</div>
@endsection