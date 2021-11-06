<?php

namespace Database\Seeders;

use App\Models\Users\User;
use App\Models\Permissions\Permission;
use App\Models\Roles\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = collect(config('permission_seeder.permissions'))->map(function($permission){
            return Permission::create(['name' => $permission['name'], 'order' => $permission['order']]);
        });

        // Create role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Assign role to user
        $admin = User::factory()->create(['name' => 'admin', 'email' => 'admin@example.com']);
        $admin->assignRole($adminRole);
    }
}
