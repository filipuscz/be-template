<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions securely
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions (Best Practice: Resource-based permissions)
        $permissions = [
            'view users',
            'create users',
            'update users',
            'delete users',
            'view examples',
            'create examples',
            'update examples',
            'delete examples',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Standard international roles
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view examples',
        ]);
    }
}
