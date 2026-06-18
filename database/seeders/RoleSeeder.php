<?php

namespace Database\Seeders;

use App\Models\Setting;
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
            'view settings',
            'update settings',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // Seed initial SMTP settings
        $settings = [
            'smtp_host' => '127.0.0.1',
            'smtp_port' => '2525',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_encryption' => 'tls',
            'mail_from_address' => 'hello@example.com',
            'mail_from_name' => 'Laravel',
        ];
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Standard international roles
        $adminRole = Role::updateOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $userRole = Role::updateOrCreate(['name' => 'user']);
        $userRole->givePermissionTo([
            'view examples',
        ]);
    }
}
