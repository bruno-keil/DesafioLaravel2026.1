<?php

use App\Models\Contact;
use App\Models\User;

describe('GET /contato', function () {
    it('renders the contact form for guests', function () {
        $this->get(route('contato.index'))
            ->assertOk()
            ->assertViewIs('contato.index');
    });

    it('renders the contact form for regular users', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('contato.index'))
            ->assertOk()
            ->assertViewIs('contato.index');
    });

    it('redirects admins to admin contact panel', function () {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('contato.index'))
            ->assertRedirect(route('admin.contato.index'));
    });
});

describe('POST /contato', function () {
    it('stores a contact message as guest', function () {
        $data = [
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'assunto' => 'Dúvida sobre produto',
            'mensagem' => 'Gostaria de saber mais sobre o produto X.',
        ];

        $this->post(route('contato.store'), $data)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contatos', [
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'assunto' => 'Dúvida sobre produto',
            'user_id' => null,
        ]);
    });

    it('stores a contact message as authenticated user with user_id', function () {
        $user = User::factory()->create();

        $data = [
            'nome' => $user->nome,
            'email' => $user->email,
            'assunto' => 'Problema com pedido',
            'mensagem' => 'Meu pedido não chegou.',
        ];

        $this->actingAs($user)
            ->post(route('contato.store'), $data)
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contatos', [
            'email' => $user->email,
            'user_id' => $user->id,
        ]);
    });

    it('requires nome field', function () {
        $this->post(route('contato.store'), [
            'email' => 'test@example.com',
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('nome');
    });

    it('requires email field', function () {
        $this->post(route('contato.store'), [
            'nome' => 'Test',
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('email');
    });

    it('requires a valid email', function () {
        $this->post(route('contato.store'), [
            'nome' => 'Test',
            'email' => 'not-an-email',
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('email');
    });

    it('requires assunto field', function () {
        $this->post(route('contato.store'), [
            'nome' => 'Test',
            'email' => 'test@example.com',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('assunto');
    });

    it('requires mensagem field', function () {
        $this->post(route('contato.store'), [
            'nome' => 'Test',
            'email' => 'test@example.com',
            'assunto' => 'Test',
        ])->assertSessionHasErrors('mensagem');
    });

    it('rejects nome longer than 255 characters', function () {
        $this->post(route('contato.store'), [
            'nome' => str_repeat('A', 256),
            'email' => 'test@example.com',
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('nome');
    });

    it('rejects mensagem longer than 5000 characters', function () {
        $this->post(route('contato.store'), [
            'nome' => 'Test',
            'email' => 'test@example.com',
            'assunto' => 'Test',
            'mensagem' => str_repeat('A', 5001),
        ])->assertSessionHasErrors('mensagem');
    });
});
