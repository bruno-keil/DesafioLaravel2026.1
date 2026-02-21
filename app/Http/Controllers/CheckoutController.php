<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function address()
    {
        $user = auth()->user();
        $cartItems = CartItem::with('product.category')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $items = $cartItems->map(function (CartItem $cartItem) {
            $product = $cartItem->product;
            return [
                'product_id' => $product->id,
                'name' => $product->nome,
                'price' => (float) $product->preco,
                'quantity' => $cartItem->quantidade,
                'stock' => (int) ($product->quantidade ?? 0),
                'photo' => get_product_photo($product),
                'category' => $product->category?->nome ?? 'Loot',
            ];
        })->values()->all();

        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        
        $addresses = $user->addresses()->orderByDesc('is_default')->get();
        $address = $user->defaultAddress();

        $isAuthenticated = true;
        $authUserName = $user->nome;

        return view('checkout.address', compact(
            'items', 
            'subtotal', 
            'user', 
            'address',
            'addresses',
            'isAuthenticated',
            'authUserName'
        ));
    }

    public function updateAddress(Request $request)
    {
        $user = $request->user();

        $selectedAddress = $user->addresses()->findOrFail($request->input('address_id'));

        $user->addresses()->update(['is_default' => false]);
        $selectedAddress->update(['is_default' => true]);

        return redirect()->route('checkout.address')->with('success', 'Endereço selecionado!');
    }
}