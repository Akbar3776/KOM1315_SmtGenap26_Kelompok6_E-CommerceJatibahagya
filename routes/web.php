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
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'getCheckoutPage'])->name('checkout.index');

    // Halaman keranjang
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getCartPage'])->name('cart.index');

    // API untuk operasi pada keranjang (JSON response)
    Route::prefix('cart')->group(function () {
        Route::get('/items', [App\Http\Controllers\CartController::class, 'getCart'])->name('cart.get');
        Route::post('/add', [App\Http\Controllers\CartController::class, 'addCart'])->name('cart.add');
        Route::put('/update/{id}', [App\Http\Controllers\CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove/{id}', [App\Http\Controllers\CartController::class, 'removeCart'])->name('cart.remove');
    });
});
