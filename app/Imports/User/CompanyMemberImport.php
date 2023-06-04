<?php

namespace App\Imports\User;

use App\Models\User;
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

class CompanyMemberImport implements ToCollection, WithHeadingRow, WithValidation
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
            $user = User::create([
                "uuid" => Str::uuid(),
                "name" => $collect["name"],
                "email" => $collect["email"],
                "password" => Hash::make($collect["password"]),
                'email_verified_at' => Carbon::now(),
                'remember_token' => Str::random(60)
            ]);

            $user->companyMember()->create([
                "uuid" => Str::uuid(),
                "company_name" => $collect["company_name"],
                'company_director' => $collect["company_director"],
                'company_address' => $collect["company_address"],
                'company_profile' => $collect["company_profile"],
                'company_email' => $collect["email"],
                'phone' => $collect["phone"],
                'facsmile' => $collect["facsmile"],
            ]);

            $roles = Role::whereIn('id', [User::MEMBER_COMPANY, User::GUEST])->get();

            $user->syncRoles($roles);
        }
    }

    public function rules(): array
    {
        return [
            "email" => ['unique:users,email', 'required'],
            "name" => ['required'],
            "password" => ['required'],
            "company_name" => ["required"],
            'company_director' => ["required"],
            'company_address' => ["required"],
            'company_profile' => ["required"],
            'phone' => ["required"],
            'facsmile' => ["required"],
        ];
    }
}
