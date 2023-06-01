<?php

namespace App\Imports\User;

use App\Models\User;
use App\Models\WelderSkill;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExpertImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $user) {
            $dateBirth = ($user['date_birth'] - 25569) * 86400;

            $welderSkill = WelderSkill::where('skill_name', 'like', "%$user[skill]%")->first();

            $user = User::create([
                "name" => $user["name"],
                "email" => $user["email"],
                "password" => Hash::make($user["password"]),
            ]);

            $user->welderMember()->create([
                "uuid" => Str::uuid(),
                "welder_skill_id" => $welderSkill->id,
                "resident_id_card" => $user["nik"],
                "date_birth" => gmdate("Y-m-d", $dateBirth),
                "birth_place" => $user["birth_place"],
                "working_status" => $user["working_status"],
                "status" => $user["status"],
            ]);

            $user->expert()->create([
                "status" => $user["status"],
                "instance" => $user["instance"]
            ]);
        }
    }
}
