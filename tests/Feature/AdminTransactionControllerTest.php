<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;

function createAdminTransactionData(string $date = '2026-01-15 10:00:00', string $status = 'pago'): Transaction
{
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $category = Category::factory()->create();
    $product = Product::factory()->create(['user_id' => $seller->id, 'categoria_id' => $category->id]);

    $transaction = Transaction::create([
        'comprador_id' => $buyer->id,
        'valor_total' => 150.00,
        'data' => $date,
        'status' => $status,
    ]);

    TransactionItem::create([
        'transacao_id' => $transaction->id,
        'produto_id' => $product->id,
        'quantidade' => 3,
        'valor_unitario' => 50.00,
    ]);

    return $transaction;
}

// ─── ADMIN PURCHASES PAGE ───────────────────────────────────────────

it('allows admin to view all purchases', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    createAdminTransactionData();
    createAdminTransactionData();

    $this->actingAs($admin)
        ->get(route('admin.purchases.index'))
        ->assertOk()
        ->assertViewIs('admin.purchases.index')
        ->assertViewHas('purchases');
});

it('blocks non-admin from admin purchases page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.purchases.index'))
        ->assertForbidden();
});

it('redirects guest from admin purchases page', function () {
    $this->get(route('admin.purchases.index'))
        ->assertRedirect();
});

it('filters admin purchases by start_date', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    createAdminTransactionData('2026-01-01 10:00:00');
    createAdminTransactionData('2026-02-01 10:00:00');

    $response = $this->actingAs($admin)
        ->get(route('admin.purchases.index', ['start_date' => '2026-01-15']));

    $response->assertOk()
        ->assertViewIs('admin.purchases.index');

    $purchases = $response->viewData('purchases');
    foreach ($purchases as $purchase) {
        expect($purchase->data->greaterThanOrEqualTo(now()->parse('2026-01-15')))->toBeTrue();
    }
});

it('filters admin purchases by end_date', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    createAdminTransactionData('2026-01-01 10:00:00');
    createAdminTransactionData('2026-02-01 10:00:00');

    $response = $this->actingAs($admin)
        ->get(route('admin.purchases.index', ['end_date' => '2026-01-15']));

    $response->assertOk();

    $purchases = $response->viewData('purchases');
    foreach ($purchases as $purchase) {
        expect($purchase->data->lessThanOrEqualTo(now()->parse('2026-01-15 23:59:59')))->toBeTrue();
    }
});

it('filters admin purchases by date range', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    createAdminTransactionData('2025-12-01 10:00:00');
    createAdminTransactionData('2026-01-15 10:00:00');
    createAdminTransactionData('2026-03-01 10:00:00');

    $response = $this->actingAs($admin)
        ->get(route('admin.purchases.index', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-02-01',
        ]));

    $response->assertOk();

    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(1);
});

it('paginates admin purchases', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    for ($i = 0; $i < 20; $i++) {
        createAdminTransactionData();
    }

    $response = $this->actingAs($admin)
        ->get(route('admin.purchases.index'));

    $response->assertOk();
    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(15);
});

// ─── ADMIN SALES PAGE ───────────────────────────────────────────────

it('allows admin to view all sales', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    createAdminTransactionData();

    $this->actingAs($admin)
        ->get(route('admin.sales.index'))
        ->assertOk()
        ->assertViewIs('admin.sales.index')
        ->assertViewHas('sales');
});

it('blocks non-admin from admin sales page', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.sales.index'))
        ->assertForbidden();
});

it('redirects guest from admin sales page', function () {
    $this->get(route('admin.sales.index'))
        ->assertRedirect();
});

it('filters admin sales by date range', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    createAdminTransactionData('2025-12-01 10:00:00');
    createAdminTransactionData('2026-01-15 10:00:00');
    createAdminTransactionData('2026-03-01 10:00:00');

    $response = $this->actingAs($admin)
        ->get(route('admin.sales.index', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-02-01',
        ]));

    $response->assertOk();
    $sales = $response->viewData('sales');
    expect($sales)->toHaveCount(1);
});

it('paginates admin sales', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    for ($i = 0; $i < 20; $i++) {
        createAdminTransactionData();
    }

    $response = $this->actingAs($admin)
        ->get(route('admin.sales.index'));

    $response->assertOk();
    $sales = $response->viewData('sales');
    expect($sales)->toHaveCount(15);
});
