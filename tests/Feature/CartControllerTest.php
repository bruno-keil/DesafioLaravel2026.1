<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($this->user);
});

it('shows the cart page', function () {
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertViewIs('cart.index');
});

it('requires auth for cart', function () {
    auth()->logout();

    $this->get(route('cart.index'))
        ->assertRedirect(route('login'));
});

it('adds a product to the cart', function () {
    $product = Product::factory()->create(['quantidade' => 10]);

    $this->post(route('cart.add', $product), ['quantity' => 2])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('success');

    $cart = session('cart');
    expect($cart)->toHaveKey($product->id);
    expect($cart[$product->id]['quantity'])->toBe(2);
});

it('caps quantity at stock level', function () {
    $product = Product::factory()->create(['quantidade' => 3]);

    $this->post(route('cart.add', $product), ['quantity' => 10])
        ->assertRedirect(route('cart.index'));

    $cart = session('cart');
    expect($cart[$product->id]['quantity'])->toBe(3);
});

it('rejects adding out-of-stock item', function () {
    $product = Product::factory()->create(['quantidade' => 0]);

    $this->post(route('cart.add', $product), ['quantity' => 1])
        ->assertRedirect()
        ->assertSessionHas('error');
});

it('updates cart item quantity', function () {
    $product = Product::factory()->create(['quantidade' => 10]);

    session(['cart' => [
        $product->id => [
            'product_id' => $product->id,
            'name' => $product->nome,
            'price' => (float) $product->preco,
            'quantity' => 2,
            'stock' => 10,
            'photo' => null,
            'category' => 'Test',
        ],
    ]]);

    $this->patch(route('cart.update', $product), ['quantity' => 5])
        ->assertRedirect(route('cart.index'));

    expect(session('cart')[$product->id]['quantity'])->toBe(5);
});

it('removes an item from the cart', function () {
    $product = Product::factory()->create();

    session(['cart' => [
        $product->id => [
            'product_id' => $product->id,
            'name' => $product->nome,
            'price' => (float) $product->preco,
            'quantity' => 1,
            'stock' => 10,
            'photo' => null,
            'category' => 'Test',
        ],
    ]]);

    $this->delete(route('cart.remove', $product))
        ->assertRedirect(route('cart.index'));

    expect(session('cart'))->not->toHaveKey($product->id);
});

it('removes item if stock becomes zero on update', function () {
    $product = Product::factory()->create(['quantidade' => 0]);

    session(['cart' => [
        $product->id => [
            'product_id' => $product->id,
            'name' => $product->nome,
            'price' => (float) $product->preco,
            'quantity' => 2,
            'stock' => 5,
            'photo' => null,
            'category' => 'Test',
        ],
    ]]);

    $this->patch(route('cart.update', $product), ['quantity' => 1])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error');

    expect(session('cart'))->not->toHaveKey($product->id);
});
