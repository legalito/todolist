<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Todo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\todo>
 */
class todoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Todo::class;
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numberBetween(1, 100),
            'label' => fake()->randomElement(['Aucune', 'Important', 'Urgent', 'Personnel', 'Travail', 'Loisir']),
            'text' => fake()->sentence(),
            'category' => fake()->randomElement(['to-do', 'in-progress', 'completed']),
            'due_date' => fake()->dateTimeBetween('-1 month', '1 month'),
        ];
    }
}
