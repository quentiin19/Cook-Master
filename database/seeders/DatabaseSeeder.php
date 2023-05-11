<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        // Super admin
        \App\Models\User::factory()->create([
            'name' => 'ouss95v',
            'email' => 'ouss@gmail.com',
            'role' => 2,
            'password' => bcrypt('Test1234'),
        ]);

        // Admin
        \App\Models\User::factory()->create([
            'name' => 'jordan95v',
            'email' => 'jordan@gmail.com',
            'role' => 1,
            'password' => bcrypt('Test1234'),
        ]);

        // Basic user
        \App\Models\User::factory()->create([
            'name' => 'quentin95v',
            'email' => 'quentin@gmail.com',
            'role' => 0,
            'password' => bcrypt('Test1234'),
        ]);

        // $this->call(BrandSeeder::class);
        // $this->call(ProductSeeder::class);
    }
}
