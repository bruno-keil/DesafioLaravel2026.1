<?php

use App\Models\User;

it('requires auth for dashboard', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

it('loads the dashboard for verified user', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard');
});

it('loads the dashboard for admin user', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewIs('dashboard');
});

it('loads the homepage', function () {
    $this->get('/')
        ->assertOk()
        ->assertViewIs('welcome');
});
