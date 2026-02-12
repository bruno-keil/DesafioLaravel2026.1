<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('shows the products index page', function () {
    Category::factory()->count(2)->create();
    Product::factory()->count(3)->create();

    $this->get(route('products.index'))
        ->assertOk()
        ->assertViewIs('products.index')
        ->assertViewHas('products');
});

it('filters products by category', function () {
    $category = Category::factory()->create();
    Product::factory()->count(2)->create(['categoria_id' => $category->id]);
    Product::factory()->count(3)->create();

    $this->get(route('products.index', ['categoria' => $category->id]))
        ->assertOk()
        ->assertViewHas('products', fn ($p) => $p->count() === 2);
});

it('searches products by name', function () {
    Product::factory()->create(['nome' => 'Arduino Mega']);
    Product::factory()->create(['nome' => 'Teclado Gamer']);

    $this->get(route('products.index', ['busca' => 'Arduino']))
        ->assertOk()
        ->assertViewHas('products', fn ($p) => $p->count() === 1);
});

it('shows a single product page', function () {
    $product = Product::factory()->create();

    $this->get(route('products.show', $product))
        ->assertOk()
        ->assertViewIs('products.show')
        ->assertViewHas('product');
});

it('requires auth to view my products', function () {
    $this->get(route('products.my'))
        ->assertRedirect(route('login'));
});

it('shows only the authenticated users products', function () {
    $user = User::factory()->create();
    Product::factory()->count(2)->create(['user_id' => $user->id]);
    Product::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('products.my'))
        ->assertOk()
        ->assertViewHas('products', fn ($p) => $p->count() === 2);
});

it('prevents admins from accessing the create product form', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('products.create'))
        ->assertRedirect(route('dashboard'));
});

it('shows the create form to regular users', function () {
    $user = User::factory()->create(['is_admin' => false]);
    Category::factory()->create();

    $this->actingAs($user)
        ->get(route('products.create'))
        ->assertOk()
        ->assertViewIs('products.create');
});

it('prevents admins from storing a product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $category = Category::factory()->create();

    Storage::fake('public');

    $this->actingAs($admin)
        ->post(route('products.store'), [
            'nome' => 'Test',
            'descricao' => 'Desc',
            'preco' => 50,
            'quantidade' => 5,
            'categoria_id' => $category->id,
            'foto' => UploadedFile::fake()->image('photo.jpg'),
        ])
        ->assertStatus(403);
});

it('stores a product for a regular user', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $category = Category::factory()->create();

    Storage::fake('public');

    $this->actingAs($user)
        ->post(route('products.store'), [
            'nome' => 'Meu Produto',
            'descricao' => 'Descricao do produto',
            'preco' => 99.90,
            'quantidade' => 10,
            'categoria_id' => $category->id,
            'foto' => UploadedFile::fake()->image('photo.jpg'),
        ])
        ->assertRedirect(route('products.my'));

    $this->assertDatabaseHas('produtos', [
        'user_id' => $user->id,
        'nome' => 'Meu Produto',
    ]);
});

it('validates required fields on store', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->post(route('products.store'), [])
        ->assertSessionHasErrors(['nome', 'descricao', 'preco', 'quantidade', 'categoria_id', 'foto']);
});

it('allows owner to edit their product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('products.edit', $product))
        ->assertOk()
        ->assertViewIs('products.edit');
});

it('allows admin to edit any product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::factory()->create();

    $this->actingAs($admin)
        ->get(route('products.edit', $product))
        ->assertOk();
});

it('forbids non-owner non-admin from editing a product', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->get(route('products.edit', $product))
        ->assertStatus(403);
});

it('updates a product as owner', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create(['user_id' => $user->id, 'categoria_id' => $category->id]);

    $this->actingAs($user)
        ->put(route('products.update', $product), [
            'nome' => 'Updated Name',
            'descricao' => 'Updated desc',
            'preco' => 200,
            'quantidade' => 5,
            'categoria_id' => $category->id,
        ])
        ->assertRedirect(route('products.my'));

    expect($product->fresh()->nome)->toBe('Updated Name');
});

it('allows owner to delete their product', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('products.destroy', $product))
        ->assertRedirect(route('products.my'));

    $this->assertDatabaseMissing('produtos', ['id' => $product->id]);
});

it('allows admin to delete any product', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $product = Product::factory()->create();

    $this->actingAs($admin)
        ->delete(route('products.destroy', $product))
        ->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseMissing('produtos', ['id' => $product->id]);
});

it('forbids non-owner non-admin from deleting a product', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->delete(route('products.destroy', $product))
        ->assertStatus(403);
});

it('shows admin product index to admins', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    Product::factory()->count(3)->create();

    $this->actingAs($admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertViewIs('admin.products.index');
});
