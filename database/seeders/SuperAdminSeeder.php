<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;


class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::where('name', 'super-admin')->first();

        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'super-admin']);
        }

        $superAdmin = User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'), // Ganti 'password' dengan kata sandi yang Anda inginkan
        ]);

        $superAdmin->roles()->attach($superAdminRole);

        // $roles = ['super-admin', 'customer', 'merchant'];
        // foreach ($roles as $roleName) {
        //     Role::firstOrCreate(['name' => $roleName]);
        // }

        // $superAdmin = User::create([
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('12345678'), // Ganti 'password' dengan kata sandi yang Anda inginkan
        // ]);

        // $superAdminRoles = Role::whereIn('name', ['super-admin', 'customer', 'merchant'])->get();
        // $superAdmin->roles()->attach($superAdminRoles);
    }
}
