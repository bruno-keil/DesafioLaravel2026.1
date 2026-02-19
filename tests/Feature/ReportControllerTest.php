<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;

function createTransactionForBuyer(User $buyer, User $seller, string $date = '2026-01-15 10:00:00', string $status = 'pago'): Transaction
{
    $category = Category::factory()->create();
    $product = Product::factory()->create(['user_id' => $seller->id, 'categoria_id' => $category->id]);

    $transaction = Transaction::create([
        'comprador_id' => $buyer->id,
        'valor_total' => 100.00,
        'data' => $date,
        'status' => $status,
    ]);

    TransactionItem::create([
        'transacao_id' => $transaction->id,
        'produto_id' => $product->id,
        'quantidade' => 2,
        'valor_unitario' => 50.00,
    ]);

    return $transaction;
}

it('allows authenticated user to download purchases PDF', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($buyer)
        ->get(route('reports.purchases.pdf'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('returns purchases PDF with only the user own purchases', function () {
    $buyer = User::factory()->create();
    $otherBuyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller);
    createTransactionForBuyer($otherBuyer, $seller);

    $response = $this->actingAs($buyer)
        ->get(route('reports.purchases.pdf'));

    $response->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('filters purchases PDF by start_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-01 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-02-01 10:00:00');

    $this->actingAs($buyer)
        ->get(route('reports.purchases.pdf', ['start_date' => '2026-01-15']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('filters purchases PDF by end_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-01 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-02-01 10:00:00');

    $this->actingAs($buyer)
        ->get(route('reports.purchases.pdf', ['end_date' => '2026-01-15']))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('filters purchases PDF by date range', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2025-12-01 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-01-15 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-03-01 10:00:00');

    $this->actingAs($buyer)
        ->get(route('reports.purchases.pdf', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-02-01',
        ]))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('redirects guest from purchases PDF', function () {
    $this->get(route('reports.purchases.pdf'))
        ->assertRedirect();
});

it('allows authenticated user to download sales PDF', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($seller)
        ->get(route('reports.sales.pdf'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('returns sales PDF filtered by date range', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-10 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-02-15 10:00:00');

    $this->actingAs($seller)
        ->get(route('reports.sales.pdf', [
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-28',
        ]))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('redirects guest from sales PDF', function () {
    $this->get(route('reports.sales.pdf'))
        ->assertRedirect();
});

it('allows admin to download purchases PDF', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($admin)
        ->get(route('admin.purchases.pdf'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('blocks non-admin from admin purchases PDF', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.purchases.pdf'))
        ->assertForbidden();
});

it('filters admin purchases PDF by date range', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-10 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-02-15 10:00:00');

    $this->actingAs($admin)
        ->get(route('admin.purchases.pdf', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
        ]))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('allows admin to download purchases Excel', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($admin)
        ->get(route('admin.purchases.excel'))
        ->assertOk()
        ->assertHeader('content-disposition');
});

it('blocks non-admin from admin purchases Excel', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.purchases.excel'))
        ->assertForbidden();
});

it('filters admin purchases Excel by date range', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-10 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-03-01 10:00:00');

    $this->actingAs($admin)
        ->get(route('admin.purchases.excel', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
        ]))
        ->assertOk()
        ->assertHeader('content-disposition');
});

it('allows admin to download sales PDF', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($admin)
        ->get(route('admin.sales.pdf'))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('blocks non-admin from admin sales PDF', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.sales.pdf'))
        ->assertForbidden();
});

it('allows admin to download sales Excel', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    createTransactionForBuyer($buyer, $seller);

    $this->actingAs($admin)
        ->get(route('admin.sales.excel'))
        ->assertOk()
        ->assertHeader('content-disposition');
});

it('blocks non-admin from admin sales Excel', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.sales.excel'))
        ->assertForbidden();
});

it('filters admin sales Excel by date range', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createTransactionForBuyer($buyer, $seller, '2026-01-10 10:00:00');
    createTransactionForBuyer($buyer, $seller, '2026-03-01 10:00:00');

    $this->actingAs($admin)
        ->get(route('admin.sales.excel', [
            'start_date' => '2026-02-01',
            'end_date' => '2026-03-31',
        ]))
        ->assertOk()
        ->assertHeader('content-disposition');
});
