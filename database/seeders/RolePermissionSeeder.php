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
            'View Histories' => 'histories',
            'Import Histories' => 'histories',
            'Update Histories' => 'histories',
            'Delete Histories' => 'histories',
            'Export Histories' => 'histories',
            'View Weather' => 'weather',
            'Import Weather' => 'weather',
            'Update Weather' => 'weather',
            'Delete Weather' => 'weather',
            'Export Weather' => 'weather',
            'View Users' => 'users',
            'Create Users' => 'users',
            'Update Users' => 'users',
            'Delete Users' => 'users',
            // 'Import Users' => 'users',
            // 'Export Users' => 'users',
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
