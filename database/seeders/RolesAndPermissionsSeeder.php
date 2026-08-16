<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $models = [
            'products',
            'categories',
            'brands',
            'warehouses',
            'contacts',
            'users',
            'stock_transactions',
            'money_transactions',
        ];

        $actions = [
            'create',
            'read',
            'update',
            'delete',
        ];

        $permissions = [];

        foreach ($models as $model) {
            foreach ($actions as $action) {

                $permissionName = "{$model}.{$action}";

                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);

                $permissions[$permissionName] = $permission;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $owner = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $seller = Role::firstOrCreate([
            'name' => 'seller',
            'guard_name' => 'web',
        ]);

        $buyer = Role::firstOrCreate([
            'name' => 'buyer',
            'guard_name' => 'web',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Owner
        |--------------------------------------------------------------------------
        | Owner can do everything.
        */

        $owner->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        | Admin can do everything.
        */

        $admin->syncPermissions(
            Permission::where('guard_name', 'web')->get()
        );
        /*
        |--------------------------------------------------------------------------
        | Seller Permissions
        |--------------------------------------------------------------------------
        */

        $sellerPermissions = [
            // Products
            'products.read',

            // Categories / Brands
            'categories.read',
            'brands.read',

            // Warehouses
            'warehouses.read',

            // Contacts / Customers
            'contacts.create',
            'contacts.read',
            'contacts.update',

            // Stock
            'stock_transactions.create',
            'stock_transactions.read',

            // Money
            'money_transactions.create',
            'money_transactions.read',


        ];

        $seller->syncPermissions(
            collect($sellerPermissions)
                ->map(fn ($permission) => $permissions[$permission])
                ->filter()
        );

        /*
        |--------------------------------------------------------------------------
        | Buyer Permissions
        |--------------------------------------------------------------------------
        */

        $buyerPermissions = [
            // Products
            'products.create',
            'products.read',
            'products.update',

            // Categories / Brands
            'categories.read',
            'brands.read',

            // Warehouses
            'warehouses.read',

            // Suppliers / Contacts
            'contacts.create',
            'contacts.read',
            'contacts.update',

            // Stock
            'stock_transactions.create',
            'stock_transactions.read',
            'stock_transactions.update',

            // Money
            'money_transactions.create',
            'money_transactions.read',
        ];

        $buyer->syncPermissions(
            collect($buyerPermissions)
                ->map(fn ($permission) => $permissions[$permission])
                ->filter()
        );
    }
}
