<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($this->admin);
});

it('lists non-admin users', function () {
    User::factory()->count(3)->create(['is_admin' => false]);

    $this->get(route('admin.users.index'))
        ->assertOk()
        ->assertViewIs('admin.users.index')
        ->assertViewHas('users');
});

it('shows the create user form', function () {
    $this->get(route('admin.users.create'))
        ->assertOk()
        ->assertViewIs('admin.users.create');
});

it('stores a new regular user with address', function () {
    $data = [
        'nome' => 'Regular User',
        'email' => 'regular@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'cpf' => '98765432100',
        'telefone' => '11988888888',
        'data_nascimento' => '1995-05-15',
        'cep' => '01001000',
        'logradouro' => 'Rua User',
        'numero' => '50',
        'bairro' => 'Bairro',
        'cidade' => 'Cidade',
        'estado' => 'SP',
        'uf' => 'SP',
    ];

    $this->post(route('admin.users.store'), $data)
        ->assertRedirect(route('admin.users.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('usuarios', [
        'email' => 'regular@test.com',
        'is_admin' => false,
        'created_by' => $this->admin->id,
    ]);
});

it('validates required fields on store', function () {
    $this->post(route('admin.users.store'), [])
        ->assertSessionHasErrors(['nome', 'email', 'password', 'cpf', 'telefone', 'data_nascimento']);
});

it('shows the edit user form', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->get(route('admin.users.edit', $user))
        ->assertOk()
        ->assertViewIs('admin.users.edit');
});

it('updates a user', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $data = [
        'nome' => 'Updated User',
        'email' => $user->email,
        'cpf' => $user->cpf,
        'telefone' => '11977777777',
        'data_nascimento' => '1995-01-01',
        'cep' => '02002000',
        'logradouro' => 'Rua Updated',
        'numero' => '99',
        'bairro' => 'Updated',
        'cidade' => 'Updated City',
        'estado' => 'RJ',
        'uf' => 'RJ',
    ];

    $this->put(route('admin.users.update', $user), $data)
        ->assertRedirect(route('admin.users.index'));

    expect($user->fresh()->nome)->toBe('Updated User');
});

it('updates password when provided', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $oldPassword = $user->password;

    $data = [
        'nome' => $user->nome,
        'email' => $user->email,
        'cpf' => $user->cpf,
        'telefone' => $user->telefone,
        'data_nascimento' => $user->data_nascimento->format('Y-m-d'),
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
        'cep' => '01001000',
        'logradouro' => 'Rua',
        'numero' => '1',
        'bairro' => 'B',
        'cidade' => 'C',
        'estado' => 'SP',
        'uf' => 'SP',
    ];

    $this->put(route('admin.users.update', $user), $data)
        ->assertRedirect(route('admin.users.index'));

    expect($user->fresh()->password)->not->toBe($oldPassword);
});

it('deletes a user', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->delete(route('admin.users.destroy', $user))
        ->assertRedirect(route('admin.users.index'));

    $this->assertDatabaseMissing('usuarios', ['id' => $user->id]);
});

it('prevents admin from deleting themselves via user route', function () {
    $this->delete(route('admin.users.destroy', $this->admin))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseHas('usuarios', ['id' => $this->admin->id]);
});
