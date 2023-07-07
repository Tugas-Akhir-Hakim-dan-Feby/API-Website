<?php

namespace App\Http\Requests\JobVacancy;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JobVacancyRequestUpdate extends FormRequest
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
        return [
            "welder_skill_id" => "required|exists:welder_skills,uuid",
            "work_type" => "required",
            "placement" => "required",
            "salary" => "required|numeric",
            "deadline" => "required",
            "description" => "required",
            "contact" => "required|numeric",
        ];
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
            "document_pamphlet" => "brosur",
            "contact" => "kontak yang dihubungi",
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
