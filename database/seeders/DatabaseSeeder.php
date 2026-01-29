<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
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

        $admins = User::factory()
            ->count(9)
            ->create([
                'is_admin' => true,
                'created_by' => $admin2->id,
            ]);

        $users = User::factory()
            ->count(18)
            ->create([
                'is_admin' => false,
                'created_by' => $admin2->id,
            ]);

        $categories = Category::factory()->count(6)->create();

        $sellerIds = $users->pluck('id');
        if ($sellerIds->isEmpty()) {
            $sellerIds = $admins->pluck('id');
        }

        Product::factory()
            ->count(36)
            ->state(function () use ($sellerIds, $categories) {
                return [
                    'user_id' => $sellerIds->random(),
                    'categoria_id' => $categories->random()->id,
                ];
            })
            ->create();
    }
}
