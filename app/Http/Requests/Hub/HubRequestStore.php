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
            'hub_user_id' => 'required|foreignIdFor|max:255',
            'hub_position' => 'required|string|max:255',
            'hub_phone' => 'required|string|max:255',
            'hub_address' => 'required|text|max:255',
            'hub_status' => 'required|integer|max:255',
        ];
    }

    public function messages()
    {
        return [
            'hub_user_id.required' => 'hub_user_id is required',
            'hub_user_id.foreignIdFor' => 'hub_user is foreign',
            'hub_user_id.max' => 'hub_user_id must be at less than 255 characters',
            'hub_position.required' => 'hub_position is required',
            'hub_position.string' => 'hub_position must be at less 255 characters',
            'hub_position.max' => 'hub_position must be at less than 255 characters',
            'hub_phone.required' => 'hub_phone is required',
            'hub_phone.string' => 'hub_phone must be at less 255 characters',
            'hub_phone.max' => 'hub_phone must be at less than 255 characters',
            'hub_address.required' => 'hub_address is required',
            'hub_address.string' => 'hub_address must be at less 255 characters',
            'hub_address.max' => 'hub_address must be at less than 255 characters',
            'hub_status.required' => 'hub_status is required',
            'hub_status.integer' => 'hub_status must be at less than 255 characters',
            'hub_status.max' => 'hub_status must be at less than 255 characters',
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
