<?php

namespace App\Http\Requests\User\Branch;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminBranchRequestUpdate extends FormRequest
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
            'branch_id' => 'required|exists:branches,uuid',
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required|numeric',
            'address' => 'required',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required',
            'date_birth' => 'required',
            'nip' => 'required|numeric',
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
            'branch_id.required' => 'kolom cabang wajib diisi',
            'branch_id.exists' => 'cabang tidak ditemukan',
            'position.required' => 'kolom jabatan wajib diisi',
            'phone.required' => 'kolom telepon wajib diisi',
            'phone.numeric' => 'harap masukan angka',
            'address.required' => 'kolom alamat wajib diisi',
            'gender.required' => 'kolom jenis kelamin wajib diisi',
            'gender.in' => 'harap masukan jenis kelamin "L" atau "P"',
            'birth_place.required' => 'kolom tempat lahir wajib diisi',
            'date_birth.required' => 'kolom tanggal lahir wajib diisi',
            'nip.required' => 'kolom nip wajib diisi',
            'nip.numeric' => 'nip wajib berisi angka',
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
