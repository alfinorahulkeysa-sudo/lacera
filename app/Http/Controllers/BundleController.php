<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class BundleController extends Controller
{
    public function index(Request $request)
    {
        // Cari kategori bundle berdasarkan slug agar tidak bergantung pada id statis
        $bundleCategory = Category::where('slug', 'bundle-package')->firstOrFail();
        $bundles = Product::where('category_id', $bundleCategory->id)->paginate(12);

        // Kirim data ke view bundle.blade.php
        return view('bundle', compact('bundles'));
    }
}