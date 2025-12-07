<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Membuat Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Sesuaikan dengan password yang diinginkan
            'role' => 'Admin',
        ]);

        // Membuat HRD
        User::create([
            'name' => 'HRD User',
            'email' => 'hrd@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'HRD',
        ]);

        // Membuat Leader
        User::create([
            'name' => 'Leader User',
            'email' => 'leader@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Leader',
        ]);

        // Membuat User biasa
        User::create([
            'name' => 'Regular User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'User',
        ]);
    }
}
