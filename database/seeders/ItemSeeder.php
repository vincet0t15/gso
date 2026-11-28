<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\PpeClassification;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $officeSupplies = Type::where('name', 'Office Supplies')->first();
        $itEquipment = Type::where('name', 'IT Equipment')->first();
        $furniture = Type::where('name', 'Furniture and Fixtures')->first();
        $cleaning = Type::where('name', 'Cleaning Materials')->first();
        $medical = Type::where('name', 'Medical Supplies')->first();

        $ppeMne = PpeClassification::where('code', 'M&E')->first();
        $ppeFfb = PpeClassification::where('code', 'FFB')->first();
        $ppeSemi = PpeClassification::where('code', 'SEMI')->first();

        $pcs = Unit::where('code', 'pcs')->first();
        $box = Unit::where('code', 'box')->first();
        $ream = Unit::where('code', 'ream')->first();
        $unit = Unit::where('code', 'unit')->first();
        $set = Unit::where('code', 'set')->first();

        $items = [
            ['item_code' => 'ITM-0001', 'item_name' => 'Bond Paper A4 (Substance 20)', 'description' => 'White bond paper, 500 sheets per ream.', 'type_id' => $officeSupplies?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $ream?->id, 'unit_cost' => 180.00, 'reorder_point' => 10],
            ['item_code' => 'ITM-0002', 'item_name' => 'Ballpen (Blue)', 'description' => 'Blue ballpoint pen.', 'type_id' => $officeSupplies?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $box?->id, 'unit_cost' => 120.00, 'reorder_point' => 5],
            ['item_code' => 'ITM-0003', 'item_name' => 'Stapler (Heavy Duty)', 'description' => 'Heavy duty stapler.', 'type_id' => $officeSupplies?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $pcs?->id, 'unit_cost' => 250.00, 'reorder_point' => 3],
            ['item_code' => 'ITM-0004', 'item_name' => 'Desktop Computer', 'description' => 'Core i5, 8GB RAM, 256GB SSD.', 'type_id' => $itEquipment?->id, 'ppe_classification_id' => $ppeMne?->id, 'unit_id' => $unit?->id, 'unit_cost' => 35000.00, 'reorder_point' => 2],
            ['item_code' => 'ITM-0005', 'item_name' => 'Laser Printer', 'description' => 'Monochrome laser printer.', 'type_id' => $itEquipment?->id, 'ppe_classification_id' => $ppeMne?->id, 'unit_id' => $unit?->id, 'unit_cost' => 8500.00, 'reorder_point' => 1],
            ['item_code' => 'ITM-0006', 'item_name' => 'Office Chair (Ergonomic)', 'description' => 'Ergonomic office chair with lumbar support.', 'type_id' => $furniture?->id, 'ppe_classification_id' => $ppeFfb?->id, 'unit_id' => $pcs?->id, 'unit_cost' => 3500.00, 'reorder_point' => 5],
            ['item_code' => 'ITM-0007', 'item_name' => 'Office Table (Executive)', 'description' => 'Executive office table, 1.5m x 0.75m.', 'type_id' => $furniture?->id, 'ppe_classification_id' => $ppeFfb?->id, 'unit_id' => $pcs?->id, 'unit_cost' => 6500.00, 'reorder_point' => 2],
            ['item_code' => 'ITM-0008', 'item_name' => 'Disinfectant (1 Liter)', 'description' => 'Multi-surface disinfectant.', 'type_id' => $cleaning?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $pcs?->id, 'unit_cost' => 180.00, 'reorder_point' => 10],
            ['item_code' => 'ITM-0009', 'item_name' => 'Broom (Soft)', 'description' => 'Soft broom for indoor use.', 'type_id' => $cleaning?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $pcs?->id, 'unit_cost' => 85.00, 'reorder_point' => 5],
            ['item_code' => 'ITM-0010', 'item_name' => 'First Aid Kit', 'description' => 'Complete first aid kit.', 'type_id' => $medical?->id, 'ppe_classification_id' => $ppeSemi?->id, 'unit_id' => $set?->id, 'unit_cost' => 1200.00, 'reorder_point' => 2],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(
                ['item_code' => $item['item_code']],
                $item
            );
        }
    }
}
