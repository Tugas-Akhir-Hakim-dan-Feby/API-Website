<?php

namespace App\Http\Requests\User\WelderMember;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class WelderRequestStore extends FormRequest
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
            'welder_skill_id' => 'required|exists:welder_skills,uuid',
            'resident_id_card' => 'required|numeric',
            'date_birth' => 'required|date',
            'birth_place' => 'required',
            'working_status' => 'required|in:1,0',
            'document_certificate_school' => 'required',
            'document_pas_photo' => 'required',
            'document_certificate_competency' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'welder_skill_id' => 'keahlian welder',
            'resident_id_card' => 'nik',
            'date_birth' => 'tanggal lahir',
            'birth_place' => 'tempat lahir',
            'working_status' => 'status bekerja',
            'document_certificate_school' => 'ijazah pendidikan formal',
            'document_pas_photo' => 'pas foto formal berwarna',
            'document_certificate_competency' => 'pas foto formal berwarna',
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
