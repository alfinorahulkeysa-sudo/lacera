<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan Model Product di-import di sini

class BundleController extends Controller
{
    public function index(Request $request)
    {
        // Langsung tembak id = 6 sesuai data Bundle Package di database Anda
        $bundles = Product::where('category_id', 6)->paginate(12);

        // Kirim data ke view bundle.blade.php
        return view('bundle', compact('bundles'));
    }
}