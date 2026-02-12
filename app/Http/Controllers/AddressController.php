<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->addresses()->count() >= 10) {
            return back()->with('address-error', 'Você já possui o máximo de 10 endereços cadastrados.');
        }

        $data = $request->validate([
            'nome' => 'required|string|max:50',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'complemento' => 'nullable|string|max:255',
            'uf' => 'required|string|size:2',
        ]);

        $isFirst = $user->addresses()->count() === 0;
        $data['is_default'] = $isFirst;

        $user->addresses()->create($data);

        $redirect = $request->input('_redirect');
        if ($redirect) {
            return redirect($redirect)->with('address-success', 'Endereço adicionado com sucesso!');
        }

        return back()->with('address-success', 'Endereço adicionado com sucesso!');
    }

    public function update(Request $request, Address $address)
    {
        Gate::authorize('update', $address);

        $data = $request->validate([
            'nome' => 'required|string|max:50',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'complemento' => 'nullable|string|max:255',
            'uf' => 'required|string|size:2',
        ]);

        $address->update($data);

        return back()->with('address-success', 'Endereço atualizado com sucesso!');
    }

    public function destroy(Request $request, Address $address)
    {
        Gate::authorize('delete', $address);

        $wasDefault = $address->is_default;
        $user = $request->user();

        $address->delete();

        if ($wasDefault) {
            $next = $user->addresses()->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return back()->with('address-success', 'Endereço removido com sucesso!');
    }

    public function setDefault(Request $request, Address $address)
    {
        Gate::authorize('update', $address);

        $user = $request->user();

        $user->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('address-success', 'Endereço padrão atualizado!');
    }
}
