<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Fungsi untuk menghandle URL /kategori atau /category utama.
     * Mengalihkan otomatis ke halaman kategori pertama yang ada di database.
     */
    public function index()
    {
        $firstCategory = Category::first();
        
        if ($firstCategory) {
            return redirect()->route('category.show', $firstCategory->slug);
        }
        
        abort(404, 'Kategori belum di-seeding atau masih kosong di database.');
    }

    /**
     * Fungsi untuk menghandle URL dinamis /category/{slug}
     * Menangkap parameter pencarian (?search=...), filter harga (?min_price=..., ?max_price=...), 
     * filter rating (?rating=...), dan sorting (?sort=...) di URL
     */
    public function show(Request $request, $slug)
    {
        // Mencari kategori berdasarkan slug di database
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Inisialisasi query dasar untuk mengambil produk berdasarkan category_id
        // PERBAIKAN 1: Selalu muat rata-rata rating agar bintang produk muncul di card meskipun filter rating tidak aktif
        $productsQuery = Product::where('category_id', $category->id)
                                ->withAvg('reviews', 'rating');
        
        // 🌟 Logika Pencarian berdasarkan parameter ?search=... di URL
        $productsQuery->when($request->filled('search'), function ($query) use ($request) {
            return $query->where('name', 'like', '%' . $request->search . '%');
        });
        
        // 🌟 Logika Filter Harga Minimum (?min_price=...)
        $productsQuery->when($request->filled('min_price'), function ($query) use ($request) {
            return $query->where('price', '>=', $request->min_price);
        });

        // 🌟 Logika Filter Harga Maksimum (?max_price=...)
        $productsQuery->when($request->filled('max_price'), function ($query) use ($request) {
            return $query->where('price', '<=', $request->max_price);
        });

        // 🌟 Logika Filter Rating Minimal (?rating=...)
        // PERBAIKAN 2: Menggunakan subquery WHERE agar aman saat dikombinasikan dengan .paginate()
        $productsQuery->when($request->filled('rating'), function ($query) use ($request) {
            return $query->where(function ($subQuery) use ($request) {
                $subQuery->selectRaw('AVG(rating)')
                         ->from('reviews')
                         ->whereColumn('reviews.product_id', 'products.id');
            }, '>=', $request->rating);
        });
        
        // Logika Sorting berdasarkan parameter ?sort=... di URL
        switch ($request->query('sort')) {
            case 'terlaris':
                // Mengurutkan berdasarkan jumlah terjual paling banyak menggunakan kolom sold_count
                $productsQuery->orderBy('sold_count', 'desc');
                break;
                
            case 'harga-low':
                // Mengurutkan harga terendah ke tertinggi
                $productsQuery->orderBy('price', 'asc');
                break;
                
            case 'harga-high':
                // Mengurutkan harga tertinggi ke terendah
                $productsQuery->orderBy('price', 'desc');
                break;
                
            case 'terbaru':
            default:
                // Default: Mengurutkan dari produk yang paling baru ditambahkan berdasarkan tanggal dibuat
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }

        // 🌟 Mengambil data produk dengan paginasi 12 item per halaman
        $products = $productsQuery->paginate(12);
        
        // Mengambil semua kategori untuk tetap ditampilkan di sidebar kiri
        $categories = Category::all();

        // Mengarahkan ke resources/views/category.blade.php dengan membawa data terkait
        return view('category-detail', compact('category', 'products', 'categories'));
    }
}