<?php

namespace App\Http\Requests\User\WelderMember;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UpdateDocumentRequest extends FormRequest
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
        $data = [];

        if (request()->is_pas_photo) {
            $data = [
                'document_pas_photo' => 'required',
            ];
        }

        if (request()->is_certificate_school) {
            $data = [
                'document_certificate_school' => 'required',
            ];
        }

        if (request()->is_certificate_competency) {
            $data = [
                'document_certificate_competency' => 'required',
            ];
        }

        return $data;
    }

    public function attributes()
    {
        return [
            'document_certificate_school' => 'ijazah pendidikan formal',
            'document_pas_photo' => 'pas foto formal berwarna',
            'document_certificate_competency' => 'dokumen sertifikat',
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
