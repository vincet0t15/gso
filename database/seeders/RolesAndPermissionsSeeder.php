<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'administration.view',
            'offices.view',
            'offices.create',
            'offices.update',
            'offices.delete',
            'types.view',
            'types.create',
            'types.update',
            'types.delete',
            'ppe-classifications.view',
            'ppe-classifications.create',
            'ppe-classifications.update',
            'ppe-classifications.delete',
            'units.view',
            'units.create',
            'units.update',
            'units.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'super_admin' => $permissions,
            'admin' => $permissions,
            'gso_staff' => ['offices.view', 'offices.create', 'offices.update', 'offices.delete', 'types.view', 'types.create', 'types.update', 'types.delete', 'ppe-classifications.view', 'ppe-classifications.create', 'ppe-classifications.update', 'ppe-classifications.delete', 'units.view', 'units.create', 'units.update', 'units.delete'],
            'office_user' => ['offices.view'],
            'approver' => ['offices.view'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
