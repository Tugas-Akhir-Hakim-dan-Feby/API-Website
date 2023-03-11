<?php

namespace Database\Seeders\Tests;

use App\Models\ExpertSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExpertSkillSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            ExpertSkill::create([
                'uuid' => Str::uuid(),
                'skill_name' => 'skill name ' . $i,
                'skill_description' => 'skill description ' . $i,
            ]);
        }
    }
}
