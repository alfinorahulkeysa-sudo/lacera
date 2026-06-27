<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // 💡 Diperlukan untuk manajemen file gambar

class AdminCategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori (Halaman Utama Kelola Kategori)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data kategori beserta jumlah produk di setiap kategori secara otomatis (withCount)
        $categories = Category::withCount('products')
            ->when($search, function ($query, $search) {
                // 💡 Disempurnakan dengan Query Grouping agar 'orWhere' tidak merusak query lainnya
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            /** * 💡 CATATAN DATABASE: Kolom 'status' belum ada di tabel categories Anda saat ini.
             * Jika nanti Anda menambahkan kolom 'status' lewat migration, hapus tanda komentar di bawah ini:
             * * ->when($request->input('status'), function ($query, $status) {
             * return $query->where('status', $status);
             * })
             */
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Mempertahankan parameter pencarian saat pindah halaman (pagination)

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan halaman form tambah kategori
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan data kategori baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('category-banners', 'public');
        }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Mengisi slug otomatis dari nama, contoh: "Body Care" jadi "body-care"
            'icon' => $request->icon,
            'description' => $request->description,
            'banner' => $bannerPath,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman form edit kategori berdasarkan model binding
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('banner')) {
            // 💡 Pembersihan Berkas: Hapus file banner lama dari storage jika ada
            if ($category->banner && Storage::disk('public')->exists($category->banner)) {
                Storage::disk('public')->delete($category->banner);
            }
            
            // Simpan file banner baru
            $bannerPath = $request->file('banner')->store('category-banners', 'public');
            $category->banner = $bannerPath;
        }

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->icon = $request->icon;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus data kategori dari database
     */
    public function destroy(Category $category)
    {
        // Fitur keamanan opsional: Mencegah penghapusan jika kategori masih digunakan oleh produk
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk aktif!');
        }

        // 💡 Pembersihan Berkas: Hapus berkas gambar banner dari server sebelum data dihapus
        if ($category->banner && Storage::disk('public')->exists($category->banner)) {
            Storage::disk('public')->delete($category->banner);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}