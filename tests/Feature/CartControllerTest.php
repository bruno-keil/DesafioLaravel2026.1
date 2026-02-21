<?php

use App\Models\CartItem;
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

    $cartItem = CartItem::where('user_id', $this->user->id)
        ->where('produto_id', $product->id)
        ->first();
    expect($cartItem)->not->toBeNull();
    expect($cartItem->quantidade)->toBe(2);
});

it('caps quantity at stock level', function () {
    $product = Product::factory()->create(['quantidade' => 3]);

    $this->post(route('cart.add', $product), ['quantity' => 10])
        ->assertRedirect(route('cart.index'));

    $cartItem = CartItem::where('user_id', $this->user->id)
        ->where('produto_id', $product->id)
        ->first();
    expect($cartItem->quantidade)->toBe(3);
});

it('rejects adding out-of-stock item', function () {
    $product = Product::factory()->create(['quantidade' => 0]);

    $this->post(route('cart.add', $product), ['quantity' => 1])
        ->assertRedirect()
        ->assertSessionHas('error');
});

it('updates cart item quantity', function () {
    $product = Product::factory()->create(['quantidade' => 10]);

    CartItem::create([
        'user_id' => $this->user->id,
        'produto_id' => $product->id,
        'quantidade' => 2,
    ]);

    $this->patch(route('cart.update', $product), ['quantity' => 5])
        ->assertRedirect(route('cart.index'));

    $cartItem = CartItem::where('user_id', $this->user->id)
        ->where('produto_id', $product->id)
        ->first();
    expect($cartItem->quantidade)->toBe(5);
});

it('removes an item from the cart', function () {
    $product = Product::factory()->create();

    CartItem::create([
        'user_id' => $this->user->id,
        'produto_id' => $product->id,
        'quantidade' => 1,
    ]);

    $this->delete(route('cart.remove', $product))
        ->assertRedirect(route('cart.index'));

    expect(CartItem::where('user_id', $this->user->id)->where('produto_id', $product->id)->exists())->toBeFalse();
});

it('removes item if stock becomes zero on update', function () {
    $product = Product::factory()->create(['quantidade' => 0]);

    CartItem::create([
        'user_id' => $this->user->id,
        'produto_id' => $product->id,
        'quantidade' => 2,
    ]);

    $this->patch(route('cart.update', $product), ['quantity' => 1])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('error');

    expect(CartItem::where('user_id', $this->user->id)->where('produto_id', $product->id)->exists())->toBeFalse();
});
