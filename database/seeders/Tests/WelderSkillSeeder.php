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
        $skills = [
            "FILLET WELDER",
            "PLATE WELDER",
            "PIPE WELDER",
            "GROUP LEADER",
            "WELDING INSPECTOR BASIC",
            "WELDING INSPECTOR FOREMAN",
            "WELDING INSPECTOR STANDARD",
            "WELDING INSPECTOR PRACTITIONER",
            "WELDING INSTRUCTOR",
            "WELDING SPECIALIST/SUPERVISOR",
            "WELDING INSPECTOR COMPREHENSIVE",
            "WELDING TECHNOLOGIST/SUPERINTENDENT",
            "WELDING ENGINEER"
        ];

        foreach ($skills as $skill) {
            WelderSkill::create([
                'uuid' => Str::uuid(),
                'skill_name' => $skill,
                'skill_description' => $skill,
            ]);
        }
    }
}
