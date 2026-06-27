<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan halaman keranjang belanja
    public function index()
    {
        // 🌟 PERBAIKAN: Tambahkan 'product.promo' agar sistem keranjang bisa menghitung harga diskon
        $cartItems = CartItem::with(['product.category', 'product.promo'])
            ->where('user_id', Auth::id())
            ->get();

        // 🌟 PERBAIKAN: Bawa juga data 'promo' untuk produk rekomendasi di bawah keranjang
        $recommendations = Product::with('promo')->inRandomOrder()->take(4)->get();

        return view('cart.index', compact('cartItems', 'recommendations'));
    }

    // Menambah produk ke dalam keranjang (Dari halaman produk/promo)
    public function store(Request $request, $id)
    {
        // 🌟 PERBAIKAN: Mengambil ID secara langsung dari route dan mencari produknya di database
        $product = Product::findOrFail($id);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Jika produk sudah ada di keranjang, tambah quantity-nya
            $cartItem->increment('quantity', $request->input('quantity', 1));
        } else {
            // Jika produk belum ada, buat item baru
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id, // Sekarang nilai ini dijamin terisi dan tidak NULL
                'quantity' => $request->input('quantity', 1)
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil dimasukkan ke keranjang!');
    }

    // Memperbarui kuantitas produk dari halaman keranjang (Tombol + dan -)
    public function update(Request $request)
    {
        // 1. Validasi input yang dikirim dari form
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        // 2. Cari item keranjang berdasarkan ID dan pastikan itu milik user yang sedang login
        $cartItem = CartItem::where('user_id', Auth::id())->find($request->id);

        if ($cartItem) {
            // 3. Update quantity di database
            $cartItem->update([
                'quantity' => $request->quantity
            ]);

            return redirect()->back()->with('success', 'Keranjang belanja berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui item keranjang.');
    }

    // Menghapus satu item dari keranjang (Tombol Tempat Sampah)
    public function destroy(CartItem $cartItem)
    {
        // Pastikan hanya pemilik keranjang yang bisa menghapus
        if ($cartItem->user_id === Auth::id()) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}