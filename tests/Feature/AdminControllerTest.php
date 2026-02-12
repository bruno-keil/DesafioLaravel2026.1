<?php

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($this->admin);
});

it('lists admin users', function () {
    User::factory()->count(3)->create(['is_admin' => true, 'created_by' => $this->admin->id]);

    $this->get(route('admin.admins.index'))
        ->assertOk()
        ->assertViewIs('admin.admins.index');
});

it('shows the create admin form', function () {
    $this->get(route('admin.admins.create'))
        ->assertOk()
        ->assertViewIs('admin.admins.create');
});

it('stores a new admin with address', function () {
    $data = [
        'nome' => 'New Admin',
        'email' => 'newadmin@test.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'cpf' => '12345678901',
        'telefone' => '11999999999',
        'data_nascimento' => '1990-01-01',
        'cep' => '01001000',
        'logradouro' => 'Rua Test',
        'numero' => '100',
        'bairro' => 'Centro',
        'cidade' => 'São Paulo',
        'estado' => 'SP',
        'uf' => 'SP',
    ];

    $this->post(route('admin.admins.store'), $data)
        ->assertRedirect(route('admin.admins.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('usuarios', [
        'email' => 'newadmin@test.com',
        'is_admin' => true,
        'created_by' => $this->admin->id,
    ]);

    $newAdmin = User::where('email', 'newadmin@test.com')->first();
    $this->assertDatabaseHas('enderecos', [
        'user_id' => $newAdmin->id,
        'cep' => '01001000',
    ]);
});

it('allows admin to edit self', function () {
    $this->get(route('admin.admins.edit', $this->admin))
        ->assertOk()
        ->assertViewIs('admin.admins.edit');
});

it('allows admin to edit admin they created', function () {
    $child = User::factory()->create([
        'is_admin' => true,
        'created_by' => $this->admin->id,
    ]);

    $this->get(route('admin.admins.edit', $child))
        ->assertOk();
});

it('blocks admin from editing admin they did not create', function () {
    $other = User::factory()->create(['is_admin' => true]);

    $this->get(route('admin.admins.edit', $other))
        ->assertRedirect(route('admin.admins.index'))
        ->assertSessionHas('error');
});

it('updates an admin created by current user', function () {
    $child = User::factory()->create([
        'is_admin' => true,
        'created_by' => $this->admin->id,
    ]);

    $data = [
        'nome' => 'Updated Admin',
        'email' => $child->email,
        'cpf' => $child->cpf,
        'telefone' => '11999999999',
        'data_nascimento' => '1990-01-01',
        'cep' => '01001000',
        'logradouro' => 'Rua Atualizada',
        'numero' => '200',
        'bairro' => 'Centro',
        'cidade' => 'São Paulo',
        'estado' => 'SP',
        'uf' => 'SP',
    ];

    $this->put(route('admin.admins.update', $child), $data)
        ->assertRedirect(route('admin.admins.index'));

    expect($child->fresh()->nome)->toBe('Updated Admin');
});

it('blocks update of admin not created by current user', function () {
    $other = User::factory()->create(['is_admin' => true]);

    $this->put(route('admin.admins.update', $other), [
        'nome' => 'Hacked',
        'email' => $other->email,
        'cpf' => $other->cpf,
        'telefone' => '11999999999',
        'data_nascimento' => '1990-01-01',
        'cep' => '01001000',
        'logradouro' => 'Rua X',
        'numero' => '1',
        'bairro' => 'B',
        'cidade' => 'C',
        'estado' => 'SP',
        'uf' => 'SP',
    ])->assertStatus(403);
});

it('allows admin to delete admin they created', function () {
    $child = User::factory()->create([
        'is_admin' => true,
        'created_by' => $this->admin->id,
    ]);

    $this->delete(route('admin.admins.destroy', $child))
        ->assertRedirect(route('admin.admins.index'));

    $this->assertDatabaseMissing('usuarios', ['id' => $child->id]);
});

it('blocks deletion of admin not created by current user', function () {
    $other = User::factory()->create(['is_admin' => true]);

    $this->delete(route('admin.admins.destroy', $other))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseHas('usuarios', ['id' => $other->id]);
});

it('allows admin to delete themselves and logs out', function () {
    $this->delete(route('admin.admins.destroy', $this->admin))
        ->assertRedirect('/');

    $this->assertDatabaseMissing('usuarios', ['id' => $this->admin->id]);
});
