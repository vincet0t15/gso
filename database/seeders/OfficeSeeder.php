<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $offices = [
            ['office_name' => 'Office of the Municipal Mayor', 'office_code' => 'OMM'],
            ['office_name' => 'Office of the Municipal Vice Mayor', 'office_code' => 'OMVM'],
            ['office_name' => 'Sangguniang Bayan', 'office_code' => 'SB'],
            ['office_name' => 'Municipal Planning and Development Office', 'office_code' => 'MPDO'],
            ['office_name' => 'Municipal Budget Office', 'office_code' => 'MBO'],
            ['office_name' => "Municipal Treasurer's Office", 'office_code' => 'MTO'],
            ['office_name' => "Municipal Assessor's Office", 'office_code' => 'MASSO'],
            ['office_name' => "Municipal Accountant's Office", 'office_code' => 'MACCO'],
            ['office_name' => 'Municipal Social Welfare and Development Office', 'office_code' => 'MSWDO'],
            ['office_name' => 'Municipal Health Office', 'office_code' => 'MHO'],
            ['office_name' => 'Municipal Agriculture Office', 'office_code' => 'MAO'],
            ['office_name' => 'Municipal Engineering Office', 'office_code' => 'MEO'],
            ['office_name' => 'Municipal Civil Registrar', 'office_code' => 'MCR'],
            ['office_name' => 'General Services Office', 'office_code' => 'GSO'],
            ['office_name' => 'Human Resource Management Office', 'office_code' => 'HRMO'],
            ['office_name' => 'Municipal Disaster Risk Reduction and Management Office', 'office_code' => 'MDRRMO'],
            ['office_name' => 'Municipal Environment and Natural Resources Office', 'office_code' => 'MENRO'],
            ['office_name' => 'Municipal Information Office', 'office_code' => 'MIO'],
        ];

        foreach ($offices as $office) {
            Office::firstOrCreate(
                ['office_name' => $office['office_name']],
                $office
            );
        }
    }
}
