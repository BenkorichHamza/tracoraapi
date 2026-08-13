<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
        ]);

        $user = User::firstOrCreate(
            [
                'email' => 'owner@gmail.com',
            ],
            [
                'name' => 'Owner',
                'password' => Hash::make('azerAZER.1995'),
            ]
        );

        $user->assignRole($role);
    }
}
