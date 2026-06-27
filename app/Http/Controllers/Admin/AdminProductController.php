<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // 🌟 Import Str untuk membuat slug otomatis

class AdminProductController extends Controller
{
    /**
     * Menampilkan daftar produk beserta fitur Pencarian dan Filter Data
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        // 1. Inisialisasi query dasar beserta relasinya
        $query = Product::with(['category', 'promo']);

        // 2. Fitur Pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 3. Filter berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Filter berdasarkan Status Aktif/Nonaktif (Sesuai kondisi: stok > 0 dianggap Aktif)
        if ($request->filled('status')) {
            if ($request->status == '1') {
                $query->where('stock', '>', 0);
            } elseif ($request->status == '0') {
                $query->where('stock', '<=', 0);
            }
        }

        // 5. Filter berdasarkan Ketersediaan Stok
        if ($request->filled('stock')) {
            if ($request->stock == 'tersedia') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock == 'habis') {
                $query->where('stock', '<=', 0);
            }
        }

        // 6. Eksekusi query dengan pagination dan bawa query string filter ke halaman berikutnya
        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Menampilkan form untuk membuat produk baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Memproses penyimpanan data produk baru ke database
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Ambil semua data dari request
        $data = $request->all();

        // Buat slug otomatis berdasarkan nama produk yang diinput
        $data['slug'] = Str::slug($request->name);

        // Beri nilai default 0 untuk weight (berat) agar database tidak error kosong
        $data['weight'] = 0; 

        // 2. Cek apakah admin mengunggah gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 3. Simpan data ke database
        Product::create($data);

        // 4. Arahkan kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman form edit produk beserta data lamanya
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id); // Cari produk berdasarkan ID, jika tidak ketemu langsung error 404
        $categories = Category::all(); // Ambil semua kategori untuk dropdown pilihan
        
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Memproses pembaruan data produk lama ke database
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Validasi input data yang diubah
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();
        
        // Perbarui slug otomatis jika nama produk ikut diubah
        $data['slug'] = Str::slug($request->name);
        
        // Jaga nilai weight agar tetap 0
        $data['weight'] = 0;

        // 2. Cek apakah ada file gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Bersihkan file lama: Hapus gambar lama dari folder storage agar hemat penyimpanan server
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Simpan file gambar baru yang dimasukkan admin
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // 3. Update data di database
        $product->update($data);

        // 4. Kembali ke halaman utama produk dengan pesan sukses
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus data produk beserta berkas gambarnya dari server
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // 1. Hapus gambar dari folder penyimpanan lokal (jika ada gambarnya)
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 2. Hapus records produk dari tabel database
        $product->delete();

        // 3. Kembali ke halaman utama produk dengan notifikasi sukses
        return redirect()->route('admin.products.index')
                         ->with('success', 'Data produk beserta gambarnya berhasil dihapus!');
    }
}