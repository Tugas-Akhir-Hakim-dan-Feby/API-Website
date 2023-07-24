<?php

namespace App\Http\Requests\User\Expert;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExpertRequestUpdate extends FormRequest
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
            'name' => 'required',
            'instance' => 'required',
            // 'welder_skill_ids' => 'array',
            // 'welder_skill_ids.*' => 'required|exists:welder_skills,uuid',
            'resident_id_card' => 'required|numeric',
            'date_birth' => 'required|date',
            'birth_place' => 'required',
            'citizenship' => 'required',
            'village' => 'required',
            'district' => 'required',
            'regency' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'phone' => 'required',
            'working_status' => 'required|in:1,0',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nama lengkap',
            'instance' => 'instansi',
            'welder_skill_ids' => 'keahlian welder',
            'resident_id_card' => 'nik',
            'date_birth' => 'tanggal lahir',
            'birth_place' => 'tempat lahir',
            'citizenship' => 'kewarganegaraan',
            'village' => 'desa/kelurahan',
            'district' => 'kecamatan',
            'regency' => 'kota/kabupaten',
            'province' => 'provinsi',
            'zip_code' => 'kode pos',
            'phone' => 'telepon',
            'working_status' => 'status bekerja',
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
