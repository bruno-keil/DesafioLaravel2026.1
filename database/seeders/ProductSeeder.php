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
        
        $testUser = User::where('email', 'teste@email.com')->first();

        if ($testUser) {
            Product::factory()
                ->count(5)
                ->sequence(function ($sequence) use ($testUser, $categories) {
                    $date = now()->subDays(rand(0, 90));
                    
                    return [
                        'user_id' => $testUser->id,
                        'categoria_id' => $categories->random()->id,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                })
                ->create();
        }

        Product::factory()
            ->count(31)
            ->sequence(function ($sequence) use ($users, $categories) {
                $date = now()->subDays(rand(0, 90));
                
                return [
                    'user_id' => $users->random()->id,
                    'categoria_id' => $categories->random()->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            })
            ->create();
    }
}