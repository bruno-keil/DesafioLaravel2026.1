<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categoryId = request('categoria');
        $searchTerm = trim((string) request('busca'));
        $categories = Category::orderBy('nome')->get();
        $products = Product::with('category')
            ->when($categoryId, fn ($query) => $query->where('categoria_id', $categoryId))
            ->when($searchTerm !== '', function ($query) use ($searchTerm) {
                $likeTerm = '%' . $searchTerm . '%';
                $query->where(function ($subQuery) use ($likeTerm) {
                    $subQuery->where('nome', 'like', $likeTerm)
                        ->orWhere('descricao', 'like', $likeTerm);
                });
            })
            ->when(auth()->check(), fn ($query) => $query->where('user_id', '!=', auth()->id()))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $fallbacks = [
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=800&q=80',
        ];

        $products->setCollection(
            $products->getCollection()->values()->map(function ($product, $index) use ($fallbacks) {
                $photo = null;
                if ($product->foto) {
                    $photo = str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto);
                }
                $product->display_photo = $photo ?? $fallbacks[$index % count($fallbacks)];

                return $product;
            })
        );

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.index', compact(
            'products',
            'categories',
            'categoryId',
            'searchTerm',
            'isAuthenticated',
            'authUserName',
            'cartCount'
        ));
    }

    public function show(Product $product)
    {
        $product->load('category', 'user');

        $photo = null;
        if ($product->foto) {
            $photo = str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto);
        }
        $displayPhoto = $photo ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80';

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $isAdmin = (bool) ($user?->is_admin ?? false);
        $canPurchase = ! $isAdmin && (int) $product->quantidade > 0;
        $showAdminNote = $isAuthenticated && $isAdmin;
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('products.show', compact(
            'product',
            'displayPhoto',
            'isAuthenticated',
            'authUserName',
            'canPurchase',
            'showAdminNote',
            'cartCount'
        ));
    }
}
