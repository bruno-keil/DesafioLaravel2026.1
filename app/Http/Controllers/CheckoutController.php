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
        
        $address = $user->address;

        $isAuthenticated = true;
        $authUserName = $user->nome ?? $user->name;

        return view('checkout.address', compact(
            'items', 
            'subtotal', 
            'user', 
            'address',
            'isAuthenticated',
            'authUserName'
        ));
    }

    public function updateAddress(Request $request)
    {
        $data = $request->validate([
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'complemento' => 'nullable|string|max:255',
            'uf' => 'required|string|size:2',
        ]);

        $user = $request->user();
        
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('checkout.address')->with('success', 'Endereço atualizado com sucesso!');
    }
}