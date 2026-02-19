<?php

namespace App\Http\Controllers;

use App\Mail\AdminContactMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->get();
        $users = User::where('is_admin', false)->orderBy('nome')->get();

        return view('admin.contato.index', compact('contacts', 'users'));
    }

    public function respond(Request $request, $id)
    {
        $request->validate([
            'resposta' => 'required|string|max:5000',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->update([
            'resposta' => $request->resposta,
            'respondido_em' => now(),
        ]);

        Mail::to($contact->email)->send(new AdminContactMail(
            userName: $contact->nome,
            assunto: 'Re: ' . $contact->assunto,
            mensagem: $request->resposta,
        ));

        return back()->with('success', 'Resposta enviada com sucesso!');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:usuarios,id',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|max:5000',
        ]);

        $user = User::findOrFail($request->user_id);

        Mail::to($user->email)->send(new AdminContactMail(
            userName: $user->nome,
            assunto: $request->assunto,
            mensagem: $request->mensagem,
        ));

        return back()->with('success', 'Email enviado para ' . $user->nome . ' com sucesso!');
    }
}
