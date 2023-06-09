<?php

namespace App\Http\Requests\Branch;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BranchadminRequestStore extends FormRequest
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
        $user = User::where('id', request()->id)->first();
        return [
            'name' => 'required|string|max:255',
            'email' => ['email', 'required', Rule::unique('users')->ignore($user)],
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required',
            'status' => 'required|integer|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'kolom nama wajib diisi',
            'email.required' => 'kolom email wajib diisi',
            'email.email' => 'email tidak valid',
            'email.unique' => 'email sudah terdaftar',
            'position.required' => 'kolom jabatan wajib diisi',
            'position.string' => 'jabatan harus kurang dari 255 karakter',
            'position.max' => 'jabatan harus kurang dari 255 karakter',
            'phone.required' => 'telepon wajib diisi',
            'phone.string' => 'telepon harus kurang dari 255 karakter',
            'phone.max' => 'telepon harus kurang dari 255 karakter',
            'address.required' => 'alamat wajib diisi',
            'address.string' => 'alamat harus kurang dari 255 karakter',
            'status.required' => 'status diperlukan',
            'status.integer' => 'harap masukan dalam bentuk bilangan 1/0',
            'status.max' => 'harap masukan dalam bentuk bilangan 1/0',
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

