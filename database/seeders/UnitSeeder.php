<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $units = [
            ['code' => 'pcs', 'name' => 'Pieces', 'description' => 'Individual pieces.'],
            ['code' => 'box', 'name' => 'Box', 'description' => 'Boxed items.'],
            ['code' => 'pack', 'name' => 'Pack', 'description' => 'Packed items.'],
            ['code' => 'set', 'name' => 'Set', 'description' => 'Set of items.'],
            ['code' => 'doz', 'name' => 'Dozen', 'description' => 'Twelve pieces.'],
            ['code' => 'pair', 'name' => 'Pair', 'description' => 'Two pieces.'],
            ['code' => 'bundle', 'name' => 'Bundle', 'description' => 'Bundled items.'],
            ['code' => 'ream', 'name' => 'Ream', 'description' => '500 sheets of paper.'],
            ['code' => 'sheet', 'name' => 'Sheet', 'description' => 'Single sheet.'],
            ['code' => 'roll', 'name' => 'Roll', 'description' => 'Rolled material.'],
            ['code' => 'L', 'name' => 'Liter', 'description' => 'Liquid volume in liters.'],
            ['code' => 'ml', 'name' => 'Milliliter', 'description' => 'Liquid volume in milliliters.'],
            ['code' => 'gal', 'name' => 'Gallon', 'description' => 'Liquid volume in gallons.'],
            ['code' => 'bottle', 'name' => 'Bottle', 'description' => 'Bottled liquid.'],
            ['code' => 'can', 'name' => 'Can', 'description' => 'Canned item.'],
            ['code' => 'sachet', 'name' => 'Sachet', 'description' => 'Sachet packet.'],
            ['code' => 'kg', 'name' => 'Kilogram', 'description' => 'Weight in kilograms.'],
            ['code' => 'g', 'name' => 'Gram', 'description' => 'Weight in grams.'],
            ['code' => 'ton', 'name' => 'Ton', 'description' => 'Weight in tons.'],
            ['code' => 'm', 'name' => 'Meter', 'description' => 'Length in meters.'],
            ['code' => 'ft', 'name' => 'Foot', 'description' => 'Length in feet.'],
            ['code' => 'in', 'name' => 'Inch', 'description' => 'Length in inches.'],
            ['code' => 'unit', 'name' => 'Unit', 'description' => 'Single unit (equipment).'],
            ['code' => 'lot', 'name' => 'Lot', 'description' => 'Lot/batch.'],
            ['code' => 'kit', 'name' => 'Kit', 'description' => 'Kit of items.'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
}
