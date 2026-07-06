<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@workroute.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Worker User',
            'email' => 'worker@workroute.com',
            'password' => bcrypt('password'),
            'role' => 'worker',
        ]);

        User::create([
            'name' => 'Client User',
            'email' => 'client@workroute.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }
}
