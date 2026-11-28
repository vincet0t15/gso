<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $types = [
            ['name' => 'Office Supplies', 'description' => 'Paper, pens, folders, and other office consumables.'],
            ['name' => 'Cleaning Materials', 'description' => 'Brooms, mops, detergents, and cleaning supplies.'],
            ['name' => 'Medical Supplies', 'description' => 'Medicines, first-aid, and medical consumables.'],
            ['name' => 'Construction Materials', 'description' => 'Cement, lumber, nails, and construction supplies.'],
            ['name' => 'IT Equipment', 'description' => 'Computers, printers, and IT peripherals.'],
            ['name' => 'Furniture and Fixtures', 'description' => 'Tables, chairs, cabinets, and fixtures.'],
            ['name' => 'Vehicle Parts and Accessories', 'description' => 'Spare parts and accessories for vehicles.'],
            ['name' => 'Safety Equipment', 'description' => 'PPE, fire extinguishers, and safety gear.'],
            ['name' => 'Electrical Supplies', 'description' => 'Wires, bulbs, switches, and electrical items.'],
            ['name' => 'Plumbing Supplies', 'description' => 'Pipes, fittings, and plumbing materials.'],
        ];

        foreach ($types as $type) {
            Type::firstOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
