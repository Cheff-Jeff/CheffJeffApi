<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use App\Models\UserRight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        $faker = \Faker\Factory::create();

        $superAdmin = UserRight::factory()->create([
            'right' => 'supper-admin',
        ]);

        $admin = UserRight::factory()->create([
            'right' => 'admin',
        ]);

        $editor = UserRight::factory()->create([
            'right' => 'editor',
        ]);

        User::factory()->create([
            'right_id' => $superAdmin->id,
            'first_name' => 'Cheff',
            'last_name' => 'Jeff',
            'email' => $faker->unique()->safeEmail(),
            'password' => Str::password(),
            'salt' => Str::random(10),
        ]);

        User::factory()->create([
            'right_id' => $admin->id,
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Str::password(),
            'salt' => Str::random(10),
        ]);

        User::factory()->create([
            'right_id' => $editor->id,
            'first_name' => $faker->firstName(),
            'last_name' => $faker->lastName(),
            'email' => $faker->unique()->safeEmail(),
            'password' => Str::password(),
            'salt' => Str::random(10),
        ]);
    }
}
