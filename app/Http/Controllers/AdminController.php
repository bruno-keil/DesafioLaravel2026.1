<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $admins = User::where('is_admin', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('admin.admins.create');
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

        $admin = User::create([
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'data_nascimento' => $validated['data_nascimento'],
            'saldo' => 0.00,
            'is_admin' => true,
            'created_by' => auth()->id(),
        ]);

        $admin->address()->create([
            'cep' => $validated['cep'],
            'logradouro' => $validated['logradouro'],
            'numero' => $validated['numero'],
            'bairro' => $validated['bairro'],
            'cidade' => $validated['cidade'],
            'estado' => $validated['estado'],
            'uf' => $validated['uf'],
            'complemento' => $validated['complemento'] ?? null,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Administrador criado com sucesso.');
    }

    public function edit(User $admin)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        if ($admin->id !== auth()->id() && $admin->created_by !== auth()->id()) {
            return redirect()->route('admin.admins.index')
                ->with('error', 'Você só pode editar seu perfil ou administradores criados por você.');
        }

        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        if ($admin->id !== auth()->id() && $admin->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('usuarios')->ignore($admin->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('usuarios')->ignore($admin->id)],
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

        $data = [
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'data_nascimento' => $validated['data_nascimento'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $admin->update($data);

        $admin->address()->updateOrCreate(
            ['user_id' => $admin->id],
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

        return redirect()->route('admin.admins.index')->with('success', 'Administrador atualizado com sucesso.');
    }

    public function destroy(User $admin)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        if ($admin->id !== auth()->id() && $admin->created_by !== auth()->id()) {
            return back()->with('error', 'Você só pode excluir seu perfil ou administradores criados por você.');
        }

        if ($admin->id === auth()->id()) {
             $admin->delete();
             auth()->logout();
             return redirect('/');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Administrador removido com sucesso.');
    }
}