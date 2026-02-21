<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cartItems = CartItem::with('product.category')
            ->where('user_id', $user->id)
            ->get();

        $items = $cartItems->map(function (CartItem $cartItem) {
            $product = $cartItem->product;
            $photo = get_product_photo($product);

            return [
                'product_id' => $product->id,
                'name' => $product->nome,
                'price' => (float) $product->preco,
                'quantity' => $cartItem->quantidade,
                'stock' => (int) ($product->quantidade ?? 0),
                'photo' => $photo,
                'category' => $product->category?->nome ?? 'Loot',
            ];
        })->values()->all();

        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $isAuthenticated = true;
        $authUserName = $user->nome ?? 'Usuario';
        $cartCount = array_sum(array_column($items, 'quantity'));

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
        $user = auth()->user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('produto_id', $product->id)
            ->first();

        $currentQty = $cartItem?->quantidade ?? 0;
        $newQty = min($stock, $currentQty + $quantity);

        if ($newQty < $currentQty + $quantity) {
            session()->flash('notice', 'Quantidade ajustada para o limite de estoque.');
        }

        CartItem::updateOrCreate(
            ['user_id' => $user->id, 'produto_id' => $product->id],
            ['quantidade' => $newQty]
        );

        return redirect()->route('cart.index')->with('success', 'Item adicionado ao carrinho.');
    }

    public function update(Request $request, Product $product)
    {
        $user = auth()->user();
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('produto_id', $product->id)
            ->first();

        if (! $cartItem) {
            return redirect()->route('cart.index');
        }

        $stock = (int) ($product->quantidade ?? 0);
        $quantity = max(1, (int) $request->input('quantity', 1));
        $newQty = min($stock, $quantity);

        if ($newQty < $quantity) {
            session()->flash('notice', 'Quantidade ajustada para o limite de estoque.');
        }

        if ($stock < 1) {
            $cartItem->delete();

            return redirect()->route('cart.index')->with('error', 'Este item ficou indisponivel e foi removido.');
        }

        $cartItem->update(['quantidade' => $newQty]);

        return redirect()->route('cart.index')->with('success', 'Carrinho atualizado.');
    }

    public function remove(Product $product)
    {
        $user = auth()->user();
        CartItem::where('user_id', $user->id)
            ->where('produto_id', $product->id)
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Item removido do carrinho.');
    }
}
