<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Panggil seeder RolesTableSeeder terlebih dahulu
        $this->call(RolesTableSeeder::class);

        // Panggil seeder SuperAdminSeeder
        $this->call(SuperAdminSeeder::class);
    }
}
