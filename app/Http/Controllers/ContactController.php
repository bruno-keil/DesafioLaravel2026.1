<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        if (auth()->user()?->is_admin) {
            return redirect()->route('admin.contato.index');
        }

        $isAuthenticated = auth()->check();
        $authUserName = auth()->user()?->nome;

        return view('contato.index', compact('isAuthenticated', 'authUserName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|max:5000',
        ]);

        Contact::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'assunto' => $request->assunto,
            'mensagem' => $request->mensagem,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }
}
