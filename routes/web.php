<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Route untuk landing page (terbuka untuk semua)
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Group route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Produk
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'getAll'])->name('products.all');
    Route::get('/products/{productId}', [App\Http\Controllers\ProductController::class, 'getDetail'])->name('products.detail');

    // Cart
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
});
