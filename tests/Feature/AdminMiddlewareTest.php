<?php

use App\Models\User;

it('blocks guests from admin routes', function () {
    $this->get(route('admin.users.index'))
        ->assertRedirect('/login');
});

it('blocks regular users from admin routes', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.users.index'))
        ->assertStatus(403);
});

it('allows admins to access admin routes', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.users.index'))
        ->assertOk();
});

it('blocks regular users from admin admins routes', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.admins.index'))
        ->assertStatus(403);
});

it('blocks regular users from admin products routes', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('admin.products.index'))
        ->assertStatus(403);
});
