<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BestSellerController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminPromoController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserReviewController;

// ✅ FIX: Tambahkan nama 'home' pada route landing page
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Railway healthcheck endpoint
Route::get('/health', function () {
    return response('OK', 200);
});

/**
 * --------------------------------------------------------------------------
 * Rute Aplikasi Terproteksi (Harus Login & Email Terverifikasi)
 * --------------------------------------------------------------------------
 */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kategori
    Route::get('/kategori', [CategoryController::class, 'index'])->name('kategori');
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

    // Best Seller
    Route::get('/best-seller', [BestSellerController::class, 'index'])->name('best-seller.index');

    // Bundle
    Route::get('/bundle', [BundleController::class, 'index'])->name('bundle.index');

    // Promo
    Route::get('/promo', [PromoController::class, 'index'])->name('promo.index');

    // Detail Produk
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::post('/product/{id}/review', [UserReviewController::class, 'store'])->name('review.store');

    // Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout & Payment
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/api/cities/{province_id}', [CheckoutController::class, 'getCities'])->name('checkout.cities');
    Route::post('/api/shipping-cost', [CheckoutController::class, 'getShippingCost'])->name('checkout.shipping_cost');

    // ✅ FIX: Pesanan — tambah route orders.show yang sebelumnya tidak ada
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');           // ← BARU
    Route::get('/orders/{id}/print', [OrderController::class, 'printInvoice'])->name('orders.print');
});

/**
 * --------------------------------------------------------------------------
 * Profil Pengguna
 * --------------------------------------------------------------------------
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Lacak Pesanan (Publik)
Route::get('/track-order', [TrackController::class, 'index'])->name('track.index');
Route::post('/track-order', [TrackController::class, 'search'])->name('track.search');

// Ulasan Pelanggan (Publik)
Route::get('/review', [UserReviewController::class, 'index'])->name('review.index');

/**
 * --------------------------------------------------------------------------
 * Admin Panel (Lacera Control Panel)
 * --------------------------------------------------------------------------
 */
Route::prefix('lacera-panel')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('products', AdminProductController::class)->names('admin.products');
        Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
        Route::resource('promos', AdminPromoController::class)->names('admin.promos');
        Route::resource('orders', AdminOrderController::class)->names('admin.orders')->only(['index', 'show', 'update']);

        // Review Management
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::post('/reviews/{review}/disapprove', [AdminReviewController::class, 'disapprove'])->name('admin.reviews.disapprove');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

        // Sales Reports
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports');

        // Store Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/users/{user}/toggle-role', [AdminUserController::class, 'toggleRole'])->name('admin.users.toggle-role');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
});
