<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => 'required|min:8|max:255',
            'new_password' => 'required|min:8|max:255|same:confirm_password',
            'confirm_password' => 'required|min:8|max:255|same:new_password',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'kolom password yang digunakan wajib diisi',
            'current_password.min' => 'password minimal 8',
            'current_password.max' => 'password maksimal 255',
            'new_password.required' => 'kolom password baru wajib diisi',
            'new_password.min' => 'password minimal 8',
            'new_password.max' => 'password maksimal 255',
            'new_password.same' => 'password baru dengan konfirmasi password tidak sama',
            'confirm_password.required' => 'kolom password konfirmasi wajib diisi',
            'confirm_password.min' => 'password minimal 8',
            'confirm_password.max' => 'password maksimal 255',
            'confirm_password.same' => 'konfirmasi password dengan password baru tidak sama',
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
