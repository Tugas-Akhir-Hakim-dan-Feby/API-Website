<?php

namespace App\Imports\User;

use App\Http\Traits\MessageFixer;
use App\Models\User;
use App\Models\User\Expert;
use App\Models\WelderSkill;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class ExpertImport implements ToCollection, WithHeadingRow, WithValidation
{
    use MessageFixer;
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

            $user->welderMember()->create([
                "uuid" => Str::uuid(),
                "welder_skill_id" => $welderSkill->id,
                "resident_id_card" => $collect["nik"],
                "date_birth" => gmdate("Y-m-d", $dateBirth),
                "birth_place" => $collect["birth_place"],
                "working_status" => $collect["working_status"],
                "status" => $collect["status"],
            ]);

            $user->expert()->create([
                "uuid" => Str::uuid(),
                "status" => Expert::APPROVED,
                "instance" => $collect["instance"]
            ]);

            $roles = Role::whereIn('id', [User::PAKAR, User::MEMBER_WELDER])->get();

            $user->syncRoles($roles);
        }

        return;
    }

    public function rules(): array
    {
        return [
            "email" => [Rule::unique('users'), 'required'],
            "name" => ['required'],
            "password" => ['required'],
            "instance" => ['required'],
            "nik" => ['required'],
            "date_birth" => ['required'],
            "birth_place" => ['required'],
            "skill" => ['required'],
            "working_status" => ['required'],
            "status" => ['required'],
        ];
    }
}
