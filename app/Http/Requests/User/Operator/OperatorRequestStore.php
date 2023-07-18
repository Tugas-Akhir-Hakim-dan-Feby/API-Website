<?php

namespace App\Http\Requests\User\Operator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OperatorRequestStore extends FormRequest
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
            "tuk_name" => "required",
            "tuk_type" => "required",
            "tuk_code" => "required",
            "address" => "required",
            "phone" => "required|numeric|max_digits:15",
            "facsmile" => "required|numeric|max_digits:15",
            "document_logo" => "required|image|mimes:png,jpg,jpeg",
        ];
    }

    public function attributes()
    {
        return [
            "tuk_name" => "nama tuk",
            "tuk_type" => "jenis tuk",
            "tuk_code" => "kode tuk",
            "address" => "alamat",
            "phone" => "no telepon",
            "facsmile" => "no fax",
            "document_logo" => "logo",
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
