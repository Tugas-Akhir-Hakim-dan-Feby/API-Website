<?php

namespace App\Http\Requests\User\Hub;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminHubRequestUpdate extends FormRequest
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
            'email' => ['email', 'required', Rule::unique('users')->ignore($user)],
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'email.required' => 'kolom email wajib diisi',
            'email.email' => 'harap masukan email valid',
            'email.unique' => 'email sudah terdaftar',
            'name.required' => 'kolom nama wajib diisi',
            'position.required' => 'kolom jabatan wajib diisi',
            'phone.required' => 'kolom telepon wajib diisi',
            'phone.numeric' => 'harap masukan angka',
            'address.required' => 'kolom alamat wajib diisi',
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
