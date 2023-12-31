<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(30)->create();

        // Super admin
        User::create([
            'name' => 'ouss95v',
            'email' => 'ouss@gmail.com',
            'role' => 2,
            "email_verified_at" => "2021-03-25 00:00:00",
            'password' => bcrypt('Test1234'),
            'key' => Str::random(32),
            'api_key' => Str::random(32),
        ]);

        // Admin
        User::create([
            'name' => 'jordan95v',
            'email' => 'jordan@gmail.com',
            'role' => 1,
            "email_verified_at" => "2021-03-25 00:00:00",
            'password' => bcrypt('Test1234'),
            'key' => Str::random(32),
            'api_key' => Str::random(32),
        ]);

        // Basic user
        User::create([
            'name' => 'quentin95v',
            'email' => 'quentin@gmail.com',
            'role' => 0,
            "email_verified_at" => "2021-03-25 00:00:00",
            'password' => bcrypt('Test1234'),
            'is_service_provider' => 1,
            'key' => Str::random(32),
            'api_key' => Str::random(32),
        ]);

        \App\Models\Brand::factory(3)->create();
        \App\Models\Course::factory(10)->create();
        \App\Models\Product::factory(10)->create();
        \App\Models\Equipment::factory(10)->create();
        \App\Models\Room::factory(10)->create();
        \App\Models\Equiped::factory(10)->create();
        \App\Models\Event::factory(5)->create();
        \App\Models\ProductComment::factory(50)->create();
        \App\Models\Formation::factory(5)->create();
        \App\Models\UserCourse::factory(20)->create();
        \App\Models\FormationCourse::factory(5)->create();
        // \App\Models\Participed::factory(20)->create();
    }
}
