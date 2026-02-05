<?php

use App\Http\Controllers\CartController;use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagSeguroController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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
    Route::post('/checkout', [PagSeguroController::class, 'createCheckout'])->name('checkout.create');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
