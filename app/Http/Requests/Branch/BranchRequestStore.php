<?php

namespace App\Http\Requests\Branch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BranchRequestStore extends FormRequest
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
            'branch_name' => 'required|string|max:255',
            'branch_address' => 'required|string|max:255',
            'branch_phone' => 'required|string|max:255',
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
            'branch_name.required' => 'Branch name is required',
            'branch_name.string' => 'Branch name must be a string',
            'branch_name.max' => 'Branch name must be less than 255 characters',
            'branch_address.required' => 'Branch address is required',
            'branch_address.string' => 'Branch address must be a string',
            'branch_address.max' => 'Branch address must be less than 255 characters',
            'branch_phone.required' => 'Branch phone is required',
            'branch_phone.string' => 'Branch phone must be a string',
            'branch_phone.max' => 'Branch phone must be less than 255 characters',
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
