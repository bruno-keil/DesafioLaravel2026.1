<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@email.com')->first();
        $admin2 = User::where('email', 'admin2@email.com')->first();

        User::factory()->create([
            'nome' => 'Teste',
            'email' => 'teste@email.com',
            'password' => Hash::make('123456789'),
            'is_admin' => false,
            'created_by' => $admin ? $admin->id : null,
        ]);

        User::factory()
            ->count(18)
            ->create([
                'is_admin' => false,
                'created_by' => $admin2 ? $admin2->id : null,
            ]);
    }
}