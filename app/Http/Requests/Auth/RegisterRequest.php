<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:255|same:retype_password',
            'retype_password' => 'required|min:8|max:255|same:password',
            'password.required' => 'kolom password wajib diisi',
            'password.min' => 'masukan password minimal :min',
            'password.max' => 'masukan password maksimal :max',
            'password.same' => 'password tidak sama dengan kolom ulangi password',
            'retype_password.required' => 'kolom ulangi password wajib diisi',
            'retype_password.min' => 'masukan password minimal :min',
            'retype_password.max' => 'masukan password maksimal :max',
            'retype_password.same' => 'ulangi password tidak sama dengan kolom password',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'kolom nama wajib diisi',
            'email.required' => 'kolom email wajib diisi',
            'email.email' => 'email tidak valid',
            'email.unique' => 'email sudah terdaftar',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'messages' => $validator->errors(),
            'status_code' => 400
        ], 400);

        throw new ValidationException($validator, $response);
    }
}
