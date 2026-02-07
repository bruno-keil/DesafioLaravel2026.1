<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $users = User::where('is_admin', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|max:14|unique:usuarios',
            'telefone' => 'required|string|max:20',
            'data_nascimento' => 'required|date',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'data_nascimento' => $validated['data_nascimento'],
            'saldo' => 0.00,
            'is_admin' => false,
            'created_by' => auth()->id(),
        ]);

        $user->address()->create([
            'cep' => $validated['cep'],
            'logradouro' => $validated['logradouro'],
            'numero' => $validated['numero'],
            'bairro' => $validated['bairro'],
            'cidade' => $validated['cidade'],
            'estado' => $validated['estado'],
            'uf' => $validated['uf'],
            'complemento' => $validated['complemento'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($user->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('usuarios')->ignore($user->id)],
            'telefone' => 'required|string|max:20',
            'data_nascimento' => 'required|date',
            'password' => 'nullable|string|min:8|confirmed',
            'cep' => 'required|string|size:8',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'estado' => 'required|string|max:50',
            'uf' => 'required|string|size:2',
            'complemento' => 'nullable|string|max:255',
        ]);

        $userData = [
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'data_nascimento' => $validated['data_nascimento'],
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'estado' => $validated['estado'],
                'uf' => $validated['uf'],
                'complemento' => $validated['complemento'] ?? null,
            ]
        );

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir a si mesmo.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário removido com sucesso.');
    }
}