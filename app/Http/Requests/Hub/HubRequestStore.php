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
            'address' => 'required|max:255',
            'status' => 'required|integer|max:255',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'user_id is required',
            'user_id' => 'user is foreign',
            'user_id.max' => 'user_id must be at less than 255 characters',
            'position.required' => 'position is required',
            'position.string' => 'position must be at less 255 characters',
            'position.max' => 'position must be at less than 255 characters',
            'phone.required' => 'phone is required',
            'phone.string' => 'phone must be at less 255 characters',
            'phone.max' => 'phone must be at less than 255 characters',
            'address.required' => 'address is required',
            'address.string' => 'address must be at less 255 characters',
            'address.max' => 'address must be at less than 255 characters',
            'status.required' => 'status is required',
            'status.integer' => 'status must be at less than 255 characters',
            'status.max' => 'status must be at less than 255 characters',
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
