<?php

namespace Database\Factories;

use App\Models\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTypes>
 */
class UserTypesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = UserTypes::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Administrador', 'Cajero']),
        ];
    }
}
