<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'email' => fake()->safeEmail(),
            'assunto' => fake()->sentence(4),
            'mensagem' => fake()->paragraph(),
            'user_id' => null,
            'resposta' => null,
            'respondido_em' => null,
        ];
    }

    public function fromUser(User $user): static
    {
        return $this->state(fn () => [
            'nome' => $user->nome,
            'email' => $user->email,
            'user_id' => $user->id,
        ]);
    }

    public function responded(): static
    {
        return $this->state(fn () => [
            'resposta' => fake()->paragraph(),
            'respondido_em' => now(),
        ]);
    }
}
