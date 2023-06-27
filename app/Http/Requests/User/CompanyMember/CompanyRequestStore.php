<?php

namespace App\Http\Requests\User\CompanyMember;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CompanyRequestStore extends FormRequest
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
            'company_name' => 'required',
            'company_director' => 'required',
            'company_address' => 'required',
            'company_profile' => 'required',
            'company_email' => 'required|email',
            'document_company_legality' => 'required',
            'document_company_logo' => 'required',
            'phone' => 'required|numeric',
            'facsmile' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'company_name' => 'nama perusahaan',
            'company_director' => 'penanggung jawab',
            'company_address' => 'alamat perusahaan',
            'company_profile' => 'profil perusahaan',
            'company_email' => 'email perusahaan',
            'document_company_legality' => 'legalitas perusahaan',
            'document_company_logo' => 'logo perusahaan',
            'phone' => 'no telepon',
            'facsmile' => 'fax',
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
