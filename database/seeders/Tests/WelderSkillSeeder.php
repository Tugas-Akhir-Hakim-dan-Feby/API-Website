<?php

namespace Database\Seeders\Tests;

use App\Models\WelderSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WelderSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            WelderSkill::create([
                'uuid' => Str::uuid(),
                'skill_name' => 'skill name ' . $i,
                'skill_description' => 'skill description ' . $i,
            ]);
        }
    }
}
