<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories = Category::factory()->count(6)->create();
        }

        Product::factory()
            ->count(36)
            ->sequence(function ($sequence) use ($users, $categories) {
                return [
                    'user_id' => $users->random()->id,
                    'categoria_id' => $categories->random()->id,
                ];
            })
            ->create();
    }
}