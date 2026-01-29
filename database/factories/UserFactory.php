<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'cpf' => str_pad((string) fake()->unique()->numberBetween(1, 99999999999), 11, '0', STR_PAD_LEFT),
            'telefone' => fake()->numerify('###########'),
            'data_nascimento' => fake()->date(),
            'saldo' => fake()->randomFloat(2, 0, 5000),
            'foto' => null,
            'is_admin' => false,
            'created_by' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
