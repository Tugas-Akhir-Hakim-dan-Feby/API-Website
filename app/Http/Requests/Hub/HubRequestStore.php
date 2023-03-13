<?php

namespace App\Http\Requests\Hub;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class HubRequestStore extends FormRequest
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
            'user_id' => 'required|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required',
            'status' => 'required|integer|max:255',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'user_id diperlukan',
            'user_id' => 'pengguna asing',
            'user_id.max' => 'user_id harus kurang dari 255 karakter',
            'position.required' => 'position diperlukan',
            'position.string' => 'posisi harus kurang dari 255 karakter',
            'position.max' => 'posisi harus kurang dari 255 karakter',
            'phone.required' => 'telepon diperlukan',
            'phone.string' => 'telepon harus kurang dari 255 karakter',
            'phone.max' => 'telepon harus kurang dari 255 karakter',
            'address.required' => 'address diperlukan',
            'address.string' => 'alamat harus kurang dari 255 karakter',
            'status.required' => 'status diperlukan',
            'status.integer' => 'status harus kurang dari 255 karakter',
            'status.max' => 'status harus kurang dari 255 karakter',
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
