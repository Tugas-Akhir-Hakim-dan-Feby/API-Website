<?php

namespace Database\Seeders\Tests;

use App\Models\WelderSkill;
use Database\Seeders\Traits\DisableForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WelderSkillSeeder extends Seeder
{
    use DisableForeignKey, TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            "Fillet Welder",
            "Plate Welder",
            "Pipe Welder",
            "Group Welder",
            "Welding Inspector Basic",
            "Welding Inspector Foreman",
            "Welding Inspector Standard",
            "Welding Inspector Practitioner",
            "Welding Instructor",
            "Welding Specialist/Supervisor",
            "Welding Inspector Comprehensive",
            "Welding Technologist/Superintendent",
            "Welding Engineer",
        ];

        $this->truncate('welder_skills');
        $this->disableForeignKeys();

        foreach ($skills as $skill) {
            WelderSkill::create([
                'uuid' => Str::uuid(),
                'skill_name' => $skill,
                'skill_description' => $skill,
            ]);
        }

        $this->enableForeignKeys();
    }
}
