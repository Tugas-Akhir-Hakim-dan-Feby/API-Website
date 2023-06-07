<?php

namespace App\Http\Requests\User\CompanyMember;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompanyRequestUpdate extends FormRequest
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
        $user = User::where('uuid', request()->id)->first();

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user)
            ],
            'name' => 'required',
            'company_name' => 'required',
            'company_director' => 'required',
            'company_address' => 'required',
            'company_profile' => 'required',
            'company_email' => 'required|email',
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
