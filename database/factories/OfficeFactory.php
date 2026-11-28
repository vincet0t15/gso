<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Office>
 */
class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition(): array
    {
        return [
            'office_name' => fake()->unique()->company(),
            'office_code' => fake()->unique()->bothify('OFF-###'),
        ];
    }
}
