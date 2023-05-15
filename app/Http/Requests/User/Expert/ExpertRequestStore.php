<?php

namespace App\Http\Requests\User\Expert;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExpertRequestStore extends FormRequest
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
            "instance" => "required",
            "document_certificate_profession" => "required",
            "document_certificate_competency" => "required",
            "document_working_mail" => "required",
            "document_career" => "required",
        ];
    }

    public function attributes()
    {
        return [
            "instance" => "instansi",
            "document_certificate_profession" => "sertifikat gelar profesi",
            "document_certificate_competency" => "sertifikat kompetensi",
            "document_working_mail" => "surat keterangan aktif bekerja",
            "document_career" => "riwayat bekerja",
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
