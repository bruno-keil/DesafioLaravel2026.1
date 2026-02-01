<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->take(8)
            ->get();

        return view('welcome', compact('products'));
    }
}
