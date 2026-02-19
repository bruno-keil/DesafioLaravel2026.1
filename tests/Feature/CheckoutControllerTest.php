<?php

use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::fake();
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($this->user);
});

it('requires auth for checkout address', function () {
    auth()->logout();

    $this->get(route('checkout.address'))
        ->assertRedirect(route('login'));
});

it('redirects to cart when cart is empty', function () {
    $this->get(route('checkout.address'))
        ->assertRedirect(route('cart.index'));
});

it('shows checkout address page when cart has items', function () {
    session(['cart' => [
        1 => [
            'product_id' => 1,
            'name' => 'Test',
            'price' => 50.00,
            'quantity' => 2,
            'stock' => 10,
            'photo' => null,
            'category' => 'Cat',
        ],
    ]]);

    $this->get(route('checkout.address'))
        ->assertOk()
        ->assertViewIs('checkout.address');
});

it('saves a new address from checkout', function () {
    session(['cart' => [
        1 => ['product_id' => 1, 'name' => 'X', 'price' => 10, 'quantity' => 1, 'stock' => 5, 'photo' => null, 'category' => 'C'],
    ]]);

    $data = [
        'nome' => 'Casa',
        'cep' => '01001000',
        'logradouro' => 'Praca da Se',
        'numero' => '100',
        'bairro' => 'Se',
        'cidade' => 'Sao Paulo',
        'estado' => 'SP',
        'uf' => 'SP',
        '_redirect' => route('checkout.address'),
    ];

    $this->post(route('addresses.store'), $data)
        ->assertRedirect(route('checkout.address'))
        ->assertSessionHas('address-success');

    $this->assertDatabaseHas('enderecos', [
        'user_id' => $this->user->id,
        'cep' => '01001000',
    ]);
});

it('selects an existing address at checkout', function () {
    $address = Address::create([
        'user_id' => $this->user->id,
        'nome' => 'Casa',
        'cep' => '01001000',
        'logradouro' => 'Old Street',
        'numero' => '1',
        'bairro' => 'Old',
        'cidade' => 'Old City',
        'estado' => 'SP',
        'uf' => 'SP',
        'is_default' => false,
    ]);

    $this->post(route('checkout.address.update'), ['address_id' => $address->id])
        ->assertRedirect(route('checkout.address'))
        ->assertSessionHas('success');

    expect($address->fresh()->is_default)->toBeTrue();
});

it('adds a second address from checkout', function () {
    Address::create([
        'user_id' => $this->user->id,
        'nome' => 'Casa',
        'cep' => '01001000',
        'logradouro' => 'Street',
        'numero' => '1',
        'bairro' => 'Bairro',
        'cidade' => 'City',
        'estado' => 'SP',
        'uf' => 'SP',
        'is_default' => true,
    ]);

    $data = [
        'nome' => 'Trabalho',
        'cep' => '02002000',
        'logradouro' => 'New Street',
        'numero' => '200',
        'bairro' => 'New',
        'cidade' => 'New City',
        'estado' => 'RJ',
        'uf' => 'RJ',
        '_redirect' => route('checkout.address'),
    ];

    $this->post(route('addresses.store'), $data)
        ->assertRedirect(route('checkout.address'));

    expect(Address::where('user_id', $this->user->id)->count())->toBe(2);
    $this->assertDatabaseHas('enderecos', [
        'user_id' => $this->user->id,
        'logradouro' => 'New Street',
    ]);
});

it('validates address_id when selecting', function () {
    $this->post(route('checkout.address.update'), [])
        ->assertStatus(404);
});

it('validates address fields when storing from checkout', function () {
    $this->post(route('addresses.store'), [])
        ->assertSessionHasErrors(['nome', 'cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado', 'uf']);
});
