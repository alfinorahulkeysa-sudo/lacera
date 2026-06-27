<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Models\Product; // 💡 TAMBAHAN: Import model Product
use Illuminate\Http\Request;

class AdminPromoController extends Controller
{
    /**
     * Tampilkan daftar promo (Halaman Utama Kelola Promo)
     */
    public function index(Request $request)
    {
        $query = Promo::query();

        // Fitur Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        $promos = $query->latest()->paginate(10)->withQueryString();

        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Tampilkan formulir tambah promo baru
     */
    public function create()
    {
        $products = Product::all(); // 💡 TAMBAHAN: Ambil semua produk untuk pilihan di form
        return view('admin.promos.create', compact('products'));
    }

    /**
     * Simpan promo baru ke database dan hubungkan ke produk
     */
    public function store(Request $request)
    {
        // 1. Validasi input, termasuk product_id
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string|max:255',
            'value'      => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'product_id' => 'required|exists:products,id', // 💡 Wajib memilih produk
        ]);

        // 2. Simpan data Promo
        $promo = Promo::create([
            'name'       => $request->name,
            'type'       => $request->type,
            'value'      => $request->value,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        // 3. 💡 HUBUNGKAN PROMO KE PRODUK:
        // Cari produk yang dipilih admin, lalu isi kolom promo_id-nya dengan ID promo yang baru dibuat
        $product = Product::findOrFail($request->product_id);
        $product->update(['promo_id' => $promo->id]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan dan diterapkan pada produk!');
    }

    /**
     * Tampilkan formulir edit promo
     */
    public function edit(Promo $promo)
    {
        $products = Product::all(); // 💡 TAMBAHAN: Ambil data produk
        
        // Cari ID produk yang saat ini menggunakan promo ini (jika ada)
        // Ini agar dropdown di form edit otomatis memilih produk yang tepat
        $promo->product_id = Product::where('promo_id', $promo->id)->value('id');

        return view('admin.promos.edit', compact('promo', 'products'));
    }

    /**
     * Perbarui data promo di database dan perbarui hubungannya dengan produk
     */
    public function update(Request $request, Promo $promo)
    {
        // 1. Validasi data edit
        $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string|max:255',
            'value'      => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'product_id' => 'required|exists:products,id', // 💡 Wajib memilih produk
        ]);

        // 2. Perbarui data promo
        $promo->update([
            'name'       => $request->name,
            'type'       => $request->type,
            'value'      => $request->value,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ]);

        // 3. 💡 PERBARUI HUBUNGAN PRODUK:
        // Putuskan hubungan promo ini dari produk lama (set promo_id menjadi null)
        Product::where('promo_id', $promo->id)->update(['promo_id' => null]);

        // Pasang hubungan promo ini ke produk baru yang dipilih admin
        $product = Product::findOrFail($request->product_id);
        $product->update(['promo_id' => $promo->id]);

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diperbarui dan diterapkan!');
    }

    /**
     * Hapus data promo dari database
     */
    public function destroy(Promo $promo)
    {
        // 💡 Putuskan dulu hubungan produk dengan promo ini agar data produk tidak error
        Product::where('promo_id', $promo->id)->update(['promo_id' => null]);

        // Hapus data promo
        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus!');
    }
}