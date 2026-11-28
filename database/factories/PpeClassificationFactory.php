<?php

namespace Database\Factories;

use App\Models\PpeClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PpeClassification>
 */
class PpeClassificationFactory extends Factory
{
    protected $model = PpeClassification::class;

    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('??-###'),
            'name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
