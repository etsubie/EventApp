<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $hostRole = Role::create(['name' => 'host']);
        $attendeeRole = Role::create(['name' => 'attendee']);

        // Create permissions
        Permission::create(attributes: ['name' => 'manage users']);
        Permission::create(['name' => 'create events']);
        Permission::create(['name' => 'manage events']);
        Permission::create(['name' => 'view events']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'book tickets']);
        
        // Assign permissions to roles
        $adminRole->givePermissionTo(['manage users','view users', 'create events', 'manage events', 'view events']);
        $hostRole->givePermissionTo(['create events', 'manage events', 'view events']);
        $attendeeRole->givePermissionTo(['view events', 'book tickets']);
    }
}
