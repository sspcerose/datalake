<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'Super Admin']);
        $adminRole1 = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'Viewer']);

        $permissions = [
            'View Roles' => 'roles',
            'Create Roles' => 'roles',
            'Edit Roles' => 'roles',
            'Delete Roles' => 'roles',
            'View Table1' => 'histories',
            'View Weather' => 'weather',
            'Import Weather' => 'weather',
        ];
    
        foreach ($permissions as $permissionName => $permissionTo) {
            Permission::create([
                'name' => $permissionName,
                'permissionto' => $permissionTo,
            ]);
        }

        // Assign all permissions to Admin
        $adminRole->permissions()->attach(Permission::all());
        $adminRole1->permissions()->attach(Permission::all());

        // Assign limited permissions to User
        $userRole->permissions()->attach(Permission::where('name', 'View Roles')->get());
    }
}
