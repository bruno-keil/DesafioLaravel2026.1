<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\TransactionItem;

function createDateFilterTransaction(User $buyer, User $seller, string $date): Transaction
{
    $category = Category::factory()->create();
    $product = Product::factory()->create(['user_id' => $seller->id, 'categoria_id' => $category->id]);

    $transaction = Transaction::create([
        'comprador_id' => $buyer->id,
        'valor_total' => 100.00,
        'data' => $date,
        'status' => 'pago',
    ]);

    TransactionItem::create([
        'transacao_id' => $transaction->id,
        'produto_id' => $product->id,
        'quantidade' => 1,
        'valor_unitario' => 100.00,
    ]);

    return $transaction;
}

// ─── USER PURCHASES DATE FILTERING ──────────────────────────────────

it('shows user purchases page without filters', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-15 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-02-15 10:00:00');

    $this->actingAs($buyer)
        ->get(route('purchases.index'))
        ->assertOk()
        ->assertViewIs('purchases.index')
        ->assertViewHas('purchases');
});

it('filters user purchases by start_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-02-15 10:00:00');

    $response = $this->actingAs($buyer)
        ->get(route('purchases.index', ['start_date' => '2026-02-01']));

    $response->assertOk();
    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(1);
});

it('filters user purchases by end_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-02-15 10:00:00');

    $response = $this->actingAs($buyer)
        ->get(route('purchases.index', ['end_date' => '2026-01-31']));

    $response->assertOk();
    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(1);
});

it('filters user purchases by full date range', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2025-12-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-01-15 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-03-01 10:00:00');

    $response = $this->actingAs($buyer)
        ->get(route('purchases.index', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-02-01',
        ]));

    $response->assertOk();
    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(1);
});

it('preserves query string in user purchases pagination', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    for ($i = 0; $i < 20; $i++) {
        createDateFilterTransaction($buyer, $seller, '2026-01-15 10:00:00');
    }

    $response = $this->actingAs($buyer)
        ->get(route('purchases.index', ['start_date' => '2026-01-01']));

    $response->assertOk();
    // Should have 15 on page 1 with pagination
    $purchases = $response->viewData('purchases');
    expect($purchases)->toHaveCount(15);
});

// ─── USER SALES DATE FILTERING ──────────────────────────────────────

it('shows user sales page without filters', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-15 10:00:00');

    $this->actingAs($seller)
        ->get(route('sales.index'))
        ->assertOk()
        ->assertViewIs('sales.index')
        ->assertViewHas('sales');
});

it('filters user sales by start_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-02-15 10:00:00');

    $response = $this->actingAs($seller)
        ->get(route('sales.index', ['start_date' => '2026-02-01']));

    $response->assertOk();
    $sales = $response->viewData('sales');
    expect($sales)->toHaveCount(1);
});

it('filters user sales by end_date', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2026-01-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-02-15 10:00:00');

    $response = $this->actingAs($seller)
        ->get(route('sales.index', ['end_date' => '2026-01-31']));

    $response->assertOk();
    $sales = $response->viewData('sales');
    expect($sales)->toHaveCount(1);
});

it('filters user sales by full date range', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();

    createDateFilterTransaction($buyer, $seller, '2025-12-01 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-01-15 10:00:00');
    createDateFilterTransaction($buyer, $seller, '2026-03-01 10:00:00');

    $response = $this->actingAs($seller)
        ->get(route('sales.index', [
            'start_date' => '2026-01-01',
            'end_date' => '2026-02-01',
        ]));

    $response->assertOk();
    $sales = $response->viewData('sales');
    expect($sales)->toHaveCount(1);
});
