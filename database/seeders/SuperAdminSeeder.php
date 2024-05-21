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
        // Pastikan role 'super-admin' sudah ada
        $superAdminRole = Role::where('name', 'super-admin')->first();

        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'super-admin']);
        }

        // Buat pengguna super admin
        $superAdmin = User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'), // Ganti 'password' dengan kata sandi yang Anda inginkan
        ]);

        // Assign 'super-admin' role ke pengguna super admin
        $superAdmin->roles()->attach($superAdminRole);

        // Pastikan role 'super-admin', 'customer', dan 'merchant' sudah ada
        // $roles = ['super-admin', 'customer', 'merchant'];
        // foreach ($roles as $roleName) {
        //     Role::firstOrCreate(['name' => $roleName]);
        // }
        // // Buat pengguna super admin
        // $superAdmin = User::create([
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('12345678'), // Ganti 'password' dengan kata sandi yang Anda inginkan
        // ]);
        // // Assign multiple roles to super admin
        // $superAdminRoles = Role::whereIn('name', ['super-admin', 'customer', 'merchant'])->get();
        // $superAdmin->roles()->attach($superAdminRoles);
    }
}
