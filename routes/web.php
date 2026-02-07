<?php

use App\Http\Controllers\CartController;use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagSeguroController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/produtos', [ProductController::class, 'index'])->name('products.index');
Route::get('/produtos/{product}', [ProductController::class, 'show'])->name('products.show');
Route::middleware('auth')->group(function () {
    Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/carrinho/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrinho/{product}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout/address', [CheckoutController::class, 'address'])->name('checkout.address');
    Route::post('/checkout/address', [CheckoutController::class, 'updateAddress'])->name('checkout.address.update');
    Route::post('/checkout', [PagSeguroController::class, 'createCheckout'])->name('checkout.create');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/meus-produtos', [ProductController::class, 'myProducts'])->name('products.my');
    Route::get('/produtos/criar', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produtos', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produtos/{product}/editar', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produtos/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produtos/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('admins', AdminController::class);
        Route::get('products', [ProductController::class, 'adminIndex'])->name('products.index');
    });
});

require __DIR__.'/auth.php';
