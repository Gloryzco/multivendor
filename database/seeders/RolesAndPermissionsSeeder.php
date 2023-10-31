<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // define your permissions
        $permissions = [
            'add-vendor',
            'edit-vendor',
            'delete-vendor',
            'approve-vendor',
            'suspend-vendor',

            'add-store',
            'edit-store',
            'delete-store',
            'suspend-store',

            'add-user',
            'edit-user',
            'delete-user',
            'approve-user',
            'suspend-user',

            'add-product',
            'edit-product',
            'delete-product',
            'approve-product',

            'view-product',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        //Define your roles
        $superadmin = 'superadmin';
        $admin = 'admin';
        $vendor = 'vendor';
        $customer = 'customer';

        Role::create(['name' => $superadmin])->givePermissionTo(Permission::all());
        Role::create(['name' => $customer])->givePermissionTo(['view-product']);
        Role::create(['name' => $admin])->givePermissionTo([
            'add-user',
            'edit-user',
            'delete-user',
            'approve-user',
            'suspend-user',
            'approve-product',
            'delete-product',
            'add-vendor',
            'edit-vendor',
            'delete-vendor',
            'suspend-vendor',

        ]);

        Role::create(['name' => $vendor])->givePermissionTo([
            'add-store',
            'edit-store',
            'delete-store',
            'add-product',
            'edit-product',
            'delete-product',

        ]);
    }
}
