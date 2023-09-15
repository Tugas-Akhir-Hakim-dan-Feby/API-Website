<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Database\Seeders\Tests\WelderSkillSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            WelderSkillSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            CostSeeder::class,
            BenefitSeeder::class,
        ]);
    }
}
