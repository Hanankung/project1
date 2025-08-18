<?php

namespace Database\Seeders;
use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name'=> 'admin', 'email' => 'admin123@gmail.com','status'=> 'active', 'role' => 'admin', 'password' => 'admin123']);
        // User::create(['name'=> 'users', 'email' => 'users@gmail.com','status'=> 'active', 'role' => 'users', 'password' => 'users']);
        User::create(['name'=> 'member', 'email' => 'members@gmail.com','status'=> 'active', 'role' => 'member', 'password' => 'member']);
    }
}
