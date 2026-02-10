<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'nome' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('123456789'),
            'is_admin' => true,
            'created_by' => null,
        ]);

        $admin2 = User::factory()->create([
            'nome' => 'Admin 2',
            'email' => 'admin2@email.com',
            'password' => Hash::make('123456789'),
            'is_admin' => true,
            'created_by' => $admin->id,
        ]);

        User::factory()
            ->count(9)
            ->create([
                'is_admin' => true,
                'created_by' => $admin2->id,
            ]);
    }
}