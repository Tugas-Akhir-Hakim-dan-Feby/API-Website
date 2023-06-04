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
            'role_id' => Role::GUEST,
        ]);
    }

    public function createAdminHub()
    {
        $adminHub = User::factory()->create([
            'email' => 'admin.pusat@mailinator.com',
            'role_id' => Role::ADMIN_PUSAT,
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
            'role_id' => Role::ADMIN_CABANG,
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
            'company_legality' => fake()->image(storage_path("app/public/company_legality")),
            'phone' => fake()->phoneNumber(),
            'facsmile' => fake()->phoneNumber(),
        ]);

        return true;
    }

    public function createWelderMember()
    {
        $welderSkill = WelderSkill::create([
            "uuid" => Str::uuid(),
            "skill_name" => "Keahlian Satu",
            "skill_description" => "Keahlian Satu",
        ]);

        $welderMember = User::factory()->create([
            'email' => 'welder.company@mailinator.com',
            'role_id' => Role::MEMBER_WELDER,
        ]);

        $welderMember->welderMember()->create([
            "welder_skill_id" => $welderSkill->id,
            "resident_id_card" => "1234567890",
            "date_birth" => "2023-10-10",
            "birth_place" => "Indramayu",
            "working_status" => 1,
            "status" => 1,
            "certificate_school" => fake()->image(storage_path("app/public/certificate_school")),
            "pas_photo" => fake()->image(storage_path("app/public/pas_photo")),
            "uuid" => Str::uuid(),
        ]);

        return true;
    }

    public function createExpert()
    {
        $welderSkill = WelderSkill::create([
            "uuid" => Str::uuid(),
            "skill_name" => "Keahlian Dua",
            "skill_description" => "Keahlian Dua",
        ]);

        $expert = User::factory()->create([
            'email' => 'pakar@mailinator.com',
            'role_id' => Role::PAKAR,
        ]);

        $expert->welderMember()->create([
            "welder_skill_id" => $welderSkill->id,
            "resident_id_card" => "1234567890",
            "date_birth" => "2023-10-10",
            "birth_place" => "Indramayu",
            "working_status" => 1,
            "status" => 1,
            "certificate_school" => fake()->image(storage_path("app/public/certificate_school")),
            "pas_photo" => fake()->image(storage_path("app/public/pas_photo")),
            "uuid" => Str::uuid(),
        ]);

        $expert->expert()->create([
            "status" => "APPROVED",
            "instance" => "Politeknik Negeri Indramayu",
            "certificate_competency" => fake()->image(storage_path("app/public/certificate_competency")),
            "certificate_profession" => fake()->image(storage_path("app/public/certificate_profession")),
            "working_mail" => fake()->image(storage_path("app/public/working_mail")),
            "career" => fake()->image(storage_path("app/public/career")),
            "uuid" => Str::uuid(),
        ]);

        return true;
    }
}
