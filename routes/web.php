<?php

use App\Models\District;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// Route untuk landing page (terbuka untuk semua)
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Produk
Route::get('/products', [App\Http\Controllers\ProductController::class, 'getAll'])->name('products.all');
Route::get('/products/{productId}', [App\Http\Controllers\ProductController::class, 'getDetail'])->name('products.detail');

// Group route yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Home
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Cart
    Route::get('/checkout', [App\Http\Controllers\CartController::class, 'getCheckoutPage'])->name('checkout.index');

    // Halaman keranjang
    Route::get('/cart', [App\Http\Controllers\CartController::class, 'getCartPage'])->name('cart.index');

    // Profile
    Route::get('/account/profile', [App\Http\Controllers\ProfileController::class, 'showProfileForm'])->name('profile.show');
    Route::put('/account/profile/update', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');

    // Password
    Route::get('/account/password', [App\Http\Controllers\ProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/account/password/update', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

    // Address
    Route::get('address', [App\Http\Controllers\UserAddressController::class, 'index'])->name('address.index');
    Route::get('address/create', [App\Http\Controllers\UserAddressController::class, 'create'])->name('address.create');
    Route::post('address', [App\Http\Controllers\UserAddressController::class, 'store'])->name('address.store');
    Route::get('address/{address}/edit', [App\Http\Controllers\UserAddressController::class, 'edit'])->name('address.edit');
    Route::put('address/{address}', [App\Http\Controllers\UserAddressController::class, 'update'])->name('address.update');
    Route::delete('address/{address}', [App\Http\Controllers\UserAddressController::class, 'destroy'])->name('address.destroy');

    // API Address
    Route::get('api/regencies/{province_id}', function ($province_id) {
        return Regency::where('province_id', $province_id)->get();
    });
    
    Route::get('api/districts/{regency_id}', function ($regency_id) {
        return District::where('regency_id', $regency_id)->get();
    });
    
    Route::get('api/villages/{district_id}', function ($district_id) {
        return Village::where('district_id', $district_id)->get();
    });

    // API untuk operasi pada keranjang (JSON response)
    Route::prefix('cart')->group(function () {
        Route::get('/items', [App\Http\Controllers\CartController::class, 'getCart'])->name('cart.get');
        Route::post('/add', [App\Http\Controllers\CartController::class, 'addCart'])->name('cart.add');
        Route::put('/update/{id}', [App\Http\Controllers\CartController::class, 'updateCart'])->name('cart.update');
        Route::delete('/remove/{id}', [App\Http\Controllers\CartController::class, 'removeCart'])->name('cart.remove');
    });
});
