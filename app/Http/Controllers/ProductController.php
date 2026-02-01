<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(12);

        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->loadMissing(['category', 'user']);

        return view('products.show', compact('product'));
    }
}
