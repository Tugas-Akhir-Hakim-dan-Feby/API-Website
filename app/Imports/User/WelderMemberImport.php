<?php

namespace App\Imports\User;

use App\Models\User;
use App\Models\WelderSkill;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class WelderMemberImport implements ToCollection, WithHeadingRow, WithValidation
{
    use SkipsFailures;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        if ($collection->count() > 100) {
            throw new Exception("Harap masukan kurang dari 100 data!");
        }

        foreach ($collection as $collect) {
            $dateBirth = ($collect['date_birth'] - 25569) * 86400;

            $welderSkill = WelderSkill::where('skill_name', 'like', "%$collect[skill]%")->first();

            $user = User::create([
                "uuid" => Str::uuid(),
                "name" => $collect["name"],
                "email" => $collect["email"],
                "password" => Hash::make($collect["password"]),
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(60)
            ]);
            $user->membership_card = "MW-" . date('Ymd') . $user->id;
            $user->save();

            $user->welderMember()->create([
                "uuid" => Str::uuid(),
                "welder_skill_id" => $welderSkill->id,
                "resident_id_card" => $collect["nik"],
                "date_birth" => gmdate("Y-m-d", $dateBirth),
                "birth_place" => $collect["birth_place"],
                "working_status" => $collect["working_status"],
                "status" => $collect["status"],
            ]);

            $roles = Role::whereIn('id', [User::MEMBER_WELDER, User::GUEST])->get();

            $user->syncRoles($roles);
        }
    }

    public function rules(): array
    {
        return [
            "email" => [Rule::unique('users'), 'required'],
            "name" => ['required'],
            "password" => ['required'],
            "nik" => ['required'],
            "date_birth" => ['required'],
            "birth_place" => ['required'],
            "skill" => ['required'],
            "working_status" => ['required'],
            "status" => ['required'],
        ];
    }
}
