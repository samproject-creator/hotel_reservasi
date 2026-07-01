<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => fake('id_ID')->name(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => Hash::make('password'), // default password untuk testing
            'role'              => fake()->randomElement(['admin', 'petugas']),
            'no_hp'             => '08' . fake()->numerify('#########'),
            'is_active'         => true,
            'remember_token'    => Str::random(10),
        ];
    }

    /** State: role admin */
    public function admin(): static
    {
        return $this->state(fn() => ['role' => 'admin']);
    }

    /** State: role petugas */
    public function petugas(): static
    {
        return $this->state(fn() => ['role' => 'petugas']);
    }

    /** State: akun nonaktif */
    public function nonaktif(): static
    {
        return $this->state(fn() => ['is_active' => false]);
    }
}
