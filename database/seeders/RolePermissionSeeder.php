<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // reset cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ======================
        // Permissions
        // ======================
        $permissions = [
            'manage users',
            'manage products',
            'view users',
            'edit users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // ======================
        // Roles (GLOBAL for now but clean)
        // ======================
        $owner = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $employee = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'web',
        ]);

        // ======================
        // Assign permissions
        // ======================

        // Owner = full access
        $owner->syncPermissions(Permission::all());

        // Admin = limited access
        $admin->syncPermissions([
            'manage products',
            'view users',
            'edit users',
        ]);

        // Employee = minimal access
        $employee->syncPermissions([
            'manage products',
        ]);
    }
}
