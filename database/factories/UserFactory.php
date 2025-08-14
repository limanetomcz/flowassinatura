<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Fábrica para criação de instâncias do modelo User.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Senha atual utilizada pela fábrica para evitar hashing repetido.
     */
    protected static ?string $password;

    /**
     * Define o estado padrão do modelo User criado pela fábrica.
     * Retorna um array associativo com os valores padrão dos atributos.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), // Nome falso gerado aleatoriamente
            'email' => fake()->unique()->safeEmail(), // Email único e válido gerado aleatoriamente
            'email_verified_at' => now(), // Data atual para indicar email verificado
            'password' => static::$password ??= Hash::make('password'), // Senha padrão 'password' com hash
            'remember_token' => Str::random(10), // Token aleatório para lembrar sessão
            'is_admin' => false, // Flag para indicar se usuário é administrador (padrão falso)
            'company_id' => fake()->numberBetween(1, 3),
        ];
    }

    /**
     * Estado para indicar que o email do usuário não foi verificado.
     * Altera o atributo 'email_verified_at' para null.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
