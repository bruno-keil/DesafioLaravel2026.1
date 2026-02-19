<?php

use App\Mail\AdminContactMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($this->admin);
});

describe('GET /admin/contatos', function () {
    it('renders the admin contact panel', function () {
        Contact::factory()->count(3)->create();

        $this->get(route('admin.contato.index'))
            ->assertOk()
            ->assertViewIs('admin.contato.index')
            ->assertViewHas('contacts')
            ->assertViewHas('users');
    });

    it('shows only non-admin users in the user list', function () {
        $regularUser = User::factory()->create(['is_admin' => false]);
        $anotherAdmin = User::factory()->create(['is_admin' => true]);

        $response = $this->get(route('admin.contato.index'));

        $users = $response->viewData('users');
        expect($users->pluck('id')->toArray())->toContain($regularUser->id);
        expect($users->pluck('id')->toArray())->not->toContain($anotherAdmin->id);
        expect($users->pluck('id')->toArray())->not->toContain($this->admin->id);
    });

    it('denies access to non-admin users', function () {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('admin.contato.index'))
            ->assertForbidden();
    });

    it('denies access to guests', function () {
        auth()->logout();

        $this->get(route('admin.contato.index'))
            ->assertRedirect(route('login'));
    });
});

describe('POST /admin/contatos/{id}/responder', function () {
    it('responds to a contact message and sends email', function () {
        $contact = Contact::factory()->create([
            'nome' => 'Maria',
            'email' => 'maria@example.com',
            'assunto' => 'Dúvida',
        ]);

        $this->post(route('admin.contato.respond', $contact->id), [
            'resposta' => 'Obrigado pelo contato, Maria!',
        ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $contact->refresh();
        expect($contact->resposta)->toBe('Obrigado pelo contato, Maria!');
        expect($contact->respondido_em)->not->toBeNull();

        Mail::assertSent(AdminContactMail::class, function ($mail) use ($contact) {
            return $mail->hasTo($contact->email)
                && $mail->assunto === 'Re: Dúvida'
                && $mail->userName === 'Maria';
        });
    });

    it('requires resposta field', function () {
        $contact = Contact::factory()->create();

        $this->post(route('admin.contato.respond', $contact->id), [
            'resposta' => '',
        ])->assertSessionHasErrors('resposta');
    });

    it('rejects resposta longer than 5000 characters', function () {
        $contact = Contact::factory()->create();

        $this->post(route('admin.contato.respond', $contact->id), [
            'resposta' => str_repeat('A', 5001),
        ])->assertSessionHasErrors('resposta');
    });

    it('returns 404 for non-existent contact', function () {
        $this->post(route('admin.contato.respond', 99999), [
            'resposta' => 'Test response',
        ])->assertNotFound();
    });

    it('denies access to non-admin users', function () {
        $user = User::factory()->create(['is_admin' => false]);
        $contact = Contact::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.contato.respond', $contact->id), [
                'resposta' => 'Test',
            ])
            ->assertForbidden();
    });
});

describe('POST /admin/contatos/enviar-email', function () {
    it('sends an email to a selected user', function () {
        $user = User::factory()->create(['is_admin' => false]);

        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => $user->id,
            'assunto' => 'Promoção especial',
            'mensagem' => 'Aproveite nossos descontos!',
        ])
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertSent(AdminContactMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email)
                && $mail->assunto === 'Promoção especial'
                && $mail->userName === $user->nome;
        });
    });

    it('requires user_id field', function () {
        $this->post(route('admin.contato.sendEmail'), [
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('user_id');
    });

    it('requires an existing user_id', function () {
        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => 99999,
            'assunto' => 'Test',
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('user_id');
    });

    it('requires assunto field', function () {
        $user = User::factory()->create();

        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => $user->id,
            'mensagem' => 'Test message',
        ])->assertSessionHasErrors('assunto');
    });

    it('requires mensagem field', function () {
        $user = User::factory()->create();

        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => $user->id,
            'assunto' => 'Test',
        ])->assertSessionHasErrors('mensagem');
    });

    it('rejects assunto longer than 255 characters', function () {
        $user = User::factory()->create();

        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => $user->id,
            'assunto' => str_repeat('A', 256),
            'mensagem' => 'Test',
        ])->assertSessionHasErrors('assunto');
    });

    it('rejects mensagem longer than 5000 characters', function () {
        $user = User::factory()->create();

        $this->post(route('admin.contato.sendEmail'), [
            'user_id' => $user->id,
            'assunto' => 'Test',
            'mensagem' => str_repeat('A', 5001),
        ])->assertSessionHasErrors('mensagem');
    });

    it('denies access to non-admin users', function () {
        $user = User::factory()->create(['is_admin' => false]);
        $target = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->post(route('admin.contato.sendEmail'), [
                'user_id' => $target->id,
                'assunto' => 'Test',
                'mensagem' => 'Test',
            ])
            ->assertForbidden();
    });
});
