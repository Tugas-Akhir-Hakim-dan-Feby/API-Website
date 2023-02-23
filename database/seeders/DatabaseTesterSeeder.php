<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Tests\ExpertSkillSeeder;
use Illuminate\Database\Seeder;

class DatabaseTesterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleSeeder::class,
            ExpertSkillSeeder::class
        ]);
    }
}
