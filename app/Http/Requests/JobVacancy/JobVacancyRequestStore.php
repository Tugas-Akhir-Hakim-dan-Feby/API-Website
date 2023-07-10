<?php

namespace App\Http\Requests\JobVacancy;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JobVacancyRequestStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $request = [
            "welder_skill_id" => "required|exists:welder_skills,uuid",
            "work_type" => "required",
            "placement" => "required",
            "salary" => "required|numeric",
            "deadline" => "required",
            "description" => "required",
            "contact" => "required|numeric",
            "company_member_id" => "required|exists:user_company_members,uuid"
        ];



        return $request;
    }

    public function attributes()
    {
        return [
            "welder_skill_id" => "jenis lowongan",
            "work_type" => "jenis pekerjaan",
            "placement" => "penempatan kerja",
            "salary" => "perkiraan gaji",
            "deadline" => "waktu penutupan",
            "description" => "deskripsi",
            "contact" => "kontak yang dihubungi",
            "company_member_id" => "perusahaan member",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'status' => 'WARNING',
            'messages' => $validator->errors(),
            'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new ValidationException($validator, $response);
    }
}
