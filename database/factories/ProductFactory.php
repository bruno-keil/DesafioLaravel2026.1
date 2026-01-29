<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'categoria_id' => Category::factory(),
            'nome' => fake()->words(3, true),
            'descricao' => fake()->sentence(),
            'foto' => null,
            'preco' => fake()->randomFloat(2, 10, 5000),
            'quantidade' => fake()->numberBetween(0, 200),
        ];
    }
}
