<?php

namespace Database\Seeders;

use App\Models\PpeClassification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PpeClassificationSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $classifications = [
            ['code' => 'LAND', 'name' => 'Land', 'description' => 'Land and land improvements.'],
            ['code' => 'BLDG', 'name' => 'Buildings', 'description' => 'Buildings and other structures.'],
            ['code' => 'M&E', 'name' => 'Machinery and Equipment', 'description' => 'Machinery, equipment, and fixtures.'],
            ['code' => 'TE', 'name' => 'Transportation Equipment', 'description' => 'Vehicles and other transportation equipment.'],
            ['code' => 'FFB', 'name' => 'Furniture, Fixtures and Books', 'description' => 'Furniture, fixtures, and library books.'],
            ['code' => 'CIP', 'name' => 'Construction in Progress', 'description' => 'Construction in progress.'],
            ['code' => 'SEMI', 'name' => 'Semi-Expendable Property', 'description' => 'Semi-expendable property items.'],
        ];

        foreach ($classifications as $classification) {
            PpeClassification::firstOrCreate(
                ['code' => $classification['code']],
                $classification
            );
        }
    }
}
