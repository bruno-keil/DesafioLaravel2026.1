<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = array_values($cart);
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $user = auth()->user();
        $isAuthenticated = (bool) $user;
        $authUserName = $user?->nome ?? $user?->name ?? 'Usuario';
        $cartCount = array_sum(array_column($cart, 'quantity'));

        return view('cart.index', compact(
            'items',
            'subtotal',
            'isAuthenticated',
            'authUserName',
            'cartCount'
        ));
    }

    public function add(Request $request, Product $product)
    {
        $stock = (int) ($product->quantidade ?? 0);
        if ($stock < 1) {
            return back()->with('error', 'Este item esta sem estoque.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $cart = session()->get('cart', []);
        $currentQty = (int) ($cart[$product->id]['quantity'] ?? 0);
        $newQty = min($stock, $currentQty + $quantity);

        if ($newQty < $currentQty + $quantity) {
            session()->flash('notice', 'Quantidade ajustada para o limite de estoque.');
        }

        $photo = null;
        if ($product->foto) {
            $photo = str_starts_with($product->foto, 'http') ? $product->foto : asset($product->foto);
        }
        $photo = $photo ?? 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80';

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->nome,
            'price' => (float) $product->preco,
            'quantity' => $newQty,
            'stock' => $stock,
            'photo' => $photo,
            'category' => $product->category?->nome ?? 'Loot',
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item adicionado ao carrinho.');
    }

    public function update(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        if (! isset($cart[$product->id])) {
            return redirect()->route('cart.index');
        }

        $stock = (int) ($product->quantidade ?? 0);
        $quantity = max(1, (int) $request->input('quantity', 1));
        $newQty = min($stock, $quantity);

        if ($newQty < $quantity) {
            session()->flash('notice', 'Quantidade ajustada para o limite de estoque.');
        }

        if ($stock < 1) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('error', 'Este item ficou indisponivel e foi removido.');
        }

        $cart[$product->id]['quantity'] = $newQty;
        $cart[$product->id]['stock'] = $stock;
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Carrinho atualizado.');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removido do carrinho.');
    }
}
