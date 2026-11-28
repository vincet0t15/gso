<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(PpeClassificationSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ItemSeeder::class);

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
        $testUser->assignRole('super_admin');

        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin'),
            ]
        );
        $admin->syncRoles(['super_admin', 'admin', 'gso_staff', 'office_user', 'approver']);
    }
}
