<?php

use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
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

it('requires auth for createCheckout', function () {
    auth()->logout();

    $this->post(route('checkout.create'))
        ->assertRedirect(route('login'));
});

it('creates a transaction and redirects to PagSeguro on successful checkout', function () {
    $seller = User::factory()->create(['saldo' => 0]);
    $product = Product::factory()->create([
        'user_id' => $seller->id,
        'preco' => 100.00,
        'quantidade' => 10,
    ]);

    Http::fake([
        '*' => Http::response([
            'links' => [
                ['rel' => 'SELF', 'href' => 'https://pagseguro.uol.com.br/self'],
                ['rel' => 'PAY', 'href' => 'https://pagseguro.uol.com.br/pay-link'],
            ],
        ], 200),
    ]);

    session(['cart' => ['something']]);

    $items = json_encode([
        [
            'product_id' => $product->id,
            'name' => $product->nome,
            'price' => 100.00,
            'quantity' => 2,
        ],
    ]);

    $response = $this->post(route('checkout.create'), ['items' => $items]);

    $response->assertRedirect('https://pagseguro.uol.com.br/pay-link');

    $this->assertDatabaseHas('transacoes', [
        'comprador_id' => $this->user->id,
        'valor_total' => 200.00,
        'status' => 'aprovado',
    ]);

    $this->assertDatabaseHas('itens_transacoes', [
        'produto_id' => $product->id,
        'quantidade' => 2,
        'valor_unitario' => 100.00,
    ]);

    expect($product->fresh()->quantidade)->toBe(8);
    expect($seller->fresh()->saldo)->toEqual('200.00');
    expect(session('cart'))->toBeNull();
});

it('creates transaction items for multiple products', function () {
    $seller1 = User::factory()->create(['saldo' => 0]);
    $seller2 = User::factory()->create(['saldo' => 0]);

    $product1 = Product::factory()->create([
        'user_id' => $seller1->id,
        'preco' => 50.00,
        'quantidade' => 20,
    ]);
    $product2 = Product::factory()->create([
        'user_id' => $seller2->id,
        'preco' => 30.00,
        'quantidade' => 15,
    ]);

    Http::fake([
        '*' => Http::response([
            'links' => [
                ['rel' => 'SELF', 'href' => 'https://pagseguro.uol.com.br/self'],
                ['rel' => 'PAY', 'href' => 'https://pagseguro.uol.com.br/pay-link'],
            ],
        ], 200),
    ]);

    $items = json_encode([
        ['product_id' => $product1->id, 'name' => $product1->nome, 'price' => 50.00, 'quantity' => 3],
        ['product_id' => $product2->id, 'name' => $product2->nome, 'price' => 30.00, 'quantity' => 5],
    ]);

    $this->post(route('checkout.create'), ['items' => $items])
        ->assertRedirect('https://pagseguro.uol.com.br/pay-link');

    $this->assertDatabaseHas('transacoes', [
        'comprador_id' => $this->user->id,
        'valor_total' => 300.00,
        'status' => 'aprovado',
    ]);

    expect(TransactionItem::count())->toBe(2);

    expect($product1->fresh()->quantidade)->toBe(17);
    expect($product2->fresh()->quantidade)->toBe(10);

    expect($seller1->fresh()->saldo)->toEqual('150.00');
    expect($seller2->fresh()->saldo)->toEqual('150.00');
});

it('clears the cart session after successful checkout', function () {
    $product = Product::factory()->create(['quantidade' => 10, 'preco' => 25.00]);

    Http::fake([
        '*' => Http::response([
            'links' => [
                ['rel' => 'SELF', 'href' => 'https://example.com/self'],
                ['rel' => 'PAY', 'href' => 'https://example.com/pay'],
            ],
        ], 200),
    ]);

    session(['cart' => ['item']]);

    $items = json_encode([
        ['product_id' => $product->id, 'name' => 'Test', 'price' => 25.00, 'quantity' => 1],
    ]);

    $this->post(route('checkout.create'), ['items' => $items]);

    expect(session('cart'))->toBeNull();
});

it('sends correct payload to PagSeguro API', function () {
    $product = Product::factory()->create(['quantidade' => 10, 'preco' => 75.50]);

    Http::fake([
        '*' => Http::response([
            'links' => [
                ['rel' => 'SELF', 'href' => 'https://example.com/self'],
                ['rel' => 'PAY', 'href' => 'https://example.com/pay'],
            ],
        ], 200),
    ]);

    $items = json_encode([
        ['product_id' => $product->id, 'name' => 'Widget', 'price' => 75.50, 'quantity' => 2],
    ]);

    $this->post(route('checkout.create'), ['items' => $items]);

    Http::assertSent(function ($request) {
        $body = json_decode($request->body(), true);

        return $body['items'][0]['name'] === 'Widget'
            && $body['items'][0]['quantity'] === 2
            && $body['items'][0]['unit_amount'] === 7550
            && str_starts_with($body['reference_id'], 'ORDER_');
    });
});

it('redirects back with errors when PagSeguro API fails', function () {
    $product = Product::factory()->create(['quantidade' => 10, 'preco' => 50.00]);

    Http::fake([
        '*' => Http::response(['error' => 'Unauthorized'], 401),
    ]);

    $items = json_encode([
        ['product_id' => $product->id, 'name' => 'Test', 'price' => 50.00, 'quantity' => 1],
    ]);

    $this->from(route('cart.index'))
        ->post(route('checkout.create'), ['items' => $items])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors();

    expect(Transaction::count())->toBe(0);
    expect($product->fresh()->quantidade)->toBe(10);
});

it('does not create transaction when PagSeguro returns server error', function () {
    $seller = User::factory()->create(['saldo' => 100.00]);
    $product = Product::factory()->create([
        'user_id' => $seller->id,
        'quantidade' => 5,
        'preco' => 40.00,
    ]);

    Http::fake([
        '*' => Http::response([], 500),
    ]);

    $items = json_encode([
        ['product_id' => $product->id, 'name' => 'Item', 'price' => 40.00, 'quantity' => 2],
    ]);

    $this->from(route('cart.index'))
        ->post(route('checkout.create'), ['items' => $items])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHasErrors();

    expect(Transaction::count())->toBe(0);
    expect(TransactionItem::count())->toBe(0);
    expect($product->fresh()->quantidade)->toBe(5);
    expect($seller->fresh()->saldo)->toEqual('100.00');
});
