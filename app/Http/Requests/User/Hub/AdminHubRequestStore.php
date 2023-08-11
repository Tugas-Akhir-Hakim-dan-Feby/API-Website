<?php

namespace App\Http\Requests\User\Hub;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AdminHubRequestStore extends FormRequest
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
            'email' => 'email|required|unique:users',
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required',
            'date_birth' => 'required',
            'membership_card' => 'required',
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
            'gender.required' => 'kolom jenis kelamin wajib diisi',
            'gender.in' => 'harap masukan jenis kelamin "L" atau "P"',
            'birth_place.required' => 'kolom tempat lahir wajib diisi',
            'date_birth.required' => 'kolom tanggal lahir wajib diisi',
            'membership_card.required' => 'kolom no kta wajib diisi',
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
