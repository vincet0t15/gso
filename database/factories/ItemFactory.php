<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\PpeClassification;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'item_code' => fake()->unique()->bothify('ITM-####'),
            'item_name' => fake()->unique()->words(3, true),
            'description' => fake()->sentence(),
            'type_id' => Type::factory(),
            'ppe_classification_id' => PpeClassification::factory(),
            'unit_id' => Unit::factory(),
            'unit_cost' => fake()->randomFloat(2, 10, 5000),
            'reorder_point' => fake()->numberBetween(0, 20),
            'is_active' => true,
        ];
    }
}
