<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class CheckoutController extends Controller
{
    public function address()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $items = array_values($cart);
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $user = auth()->user();
        
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