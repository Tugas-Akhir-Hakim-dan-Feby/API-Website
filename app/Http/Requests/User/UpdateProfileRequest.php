<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdateProfileRequest extends FormRequest
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
            'new_name' => 'required|string|max:255',
            'new_email' => 'required|string|email|max:255|unique:users,email',
            'new_password' => 'nullable|string|min:6|confirmed',
            'current_password' => 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'new_name.required' => 'kolom nama baru wajib diisi',
            'new_name.max' => 'kolom nama baru maksimal 255',
            'new_email.required' => 'kolom email baru wajib diisi',
            'new_email.max' => 'kolom email baru maksimal 255',
            'new_password.required' => 'kolom password baru wajib diisi',
            'new_password.min' => 'password minimal 8',
            'new_password.max' => 'password maksimal 255',
            'new_password.same' => 'password baru dengan konfirmasi password tidak sama',
            'current_password.required' => 'kolom password yang digunakan wajib diisi',
            'current_password.min' => 'password minimal 8',
            'current_password.max' => 'password maksimal 255',
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
