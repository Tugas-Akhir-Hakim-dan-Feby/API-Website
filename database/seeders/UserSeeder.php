<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use App\Models\WelderSkill;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as ModelsRole;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'admin.app@mailinator.com',
            'role_id' => Role::ADMIN_APP,
        ]);

        $this->createAdminHub();

        $this->createAdminBranch();

        $this->createExpert();

        $this->createWelderMember();

        $this->createCompanyMember();

        User::factory()->create([
            'email' => 'guest@mailinator.com',
            'role_id' => User::MEMBER_APPLICATION,
        ]);
    }

    public function createAdminHub()
    {
        $adminHub = User::factory()->create([
            'email' => 'admin.pusat@mailinator.com',
            'role_id' => User::ADMIN_HUB,
        ]);

        $adminHub->adminHub()->create([
            'uuid' => Str::uuid(),
            'position' => 'CEO',
            'phone' => '12121212',
            'address' => 'Indramayu',
            'status' => 1
        ]);

        return true;
    }

    public function createAdminBranch()
    {
        $branch = Branch::create([
            'uuid' => Str::uuid(),
            'branch_name' => "Chung Jui Fang",
            'branch_address' => "Jawa Tengah",
            'branch_phone' => "12121221",
        ]);

        $userBranch = User::factory()->create([
            'email' => 'admin.cabang@mailinator.com',
            'role_id' => User::ADMIN_BRANCH,
        ]);

        $userBranch->adminBranch()->create([
            'uuid' => Str::uuid(),
            'nip' => '111',
            'position' => 'CEO',
            'phone' => '12121212',
            'address' => 'Indramayu',
            'status' => 1,
            'date_birth' => Carbon::now(),
            'birth_place' => 'Indramayu',
            'branch_id' => $branch->id
        ]);

        return true;
    }

    public function createCompanyMember()
    {
        $companyMember = User::factory()->create([
            'email' => 'member.company@mailinator.com',
            'role_id' => Role::MEMBER_COMPANY,
        ]);

        $companyMember->companyMember()->create([
            "uuid" => Str::uuid(),
            "company_name" => "PT. Maju Mundur Jaya",
            'company_director' => "Casiman Sukardi S.ST",
            'company_address' => "Jl. Lohbener Lama No.08, Lohbener, Legok, Indramayu, Kabupaten Indramayu, Jawa Barat",
            'company_profile' => fake()->text(),
            'company_email' => $companyMember->email,
            'company_legality' => fake()->uuid(),
            'phone' => fake()->phoneNumber(),
            'facsmile' => fake()->phoneNumber(),
        ]);

        $companyMember->update([
            'membership_card' => "MC-" . date('Ymd') . $companyMember->id
        ]);

        return true;
    }

    public function createWelderMember()
    {
        $welderSkill = WelderSkill::where('skill_name', 'FILLET WELDER')->first();

        $welderMember = User::factory()->create([
            'email' => 'welder.company@mailinator.com',
            'role_id' => User::MEMBER_INDIVIDUAL,
        ]);

        $welderMember->welderHasSkills()->create([
            "welder_skill_id" => $welderSkill->id
        ]);

        $welderMember->welderMember()->create([
            "resident_id_card" => "1234567890",
            "date_birth" => "2023-10-10",
            "birth_place" => "Indramayu",
            "working_status" => 1,
            "status" => 1,
            // "certificate_school" => fake()->image(storage_path("app/public/certificate_school")),
            "pas_photo" => fake()->uuid(),
            "uuid" => Str::uuid(),
        ]);

        $welderMember->update([
            'membership_card' => "MW-" . date('Ymd') . $welderMember->id
        ]);

        return true;
    }

    public function createExpert()
    {
        $welderSkill = WelderSkill::where("skill_name", "PLATE WELDER")->first();

        $expert = User::factory()->create([
            'email' => 'pakar@mailinator.com',
            'role_id' => User::EXPERT,
        ]);

        $expert->welderHasSkills()->create([
            "welder_skill_id" => $welderSkill->id
        ]);

        $expert->welderMember()->create([
            "resident_id_card" => "1234567890",
            "date_birth" => "2023-10-10",
            "birth_place" => "Indramayu",
            "working_status" => 1,
            "status" => 1,
            // "certificate_school" => fake()->image(storage_path("app/public/certificate_school")),
            "pas_photo" => fake()->uuid(),
            "uuid" => Str::uuid(),
        ]);

        $expert->expert()->create([
            "status" => "APPROVED",
            "instance" => "Politeknik Negeri Indramayu",
            "certificate_competency" => fake()->uuid(),
            "certificate_profession" => fake()->uuid(),
            "working_mail" => fake()->uuid(),
            "career" => fake()->uuid(),
            "uuid" => Str::uuid(),
        ]);


        $expert->update([
            'membership_card' => "MW-" . date('Ymd') . $expert->id
        ]);

        return true;
    }
}
