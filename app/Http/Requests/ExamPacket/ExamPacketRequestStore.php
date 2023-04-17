<?php

namespace App\Http\Requests\ExamPacket;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExamPacketRequestStore extends FormRequest
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
        $request = [
            "name" => "required",
            "year" => "numeric",
            "schedule" => "required",
        ];

        if (request('is_period') == "true") {
            $request["period"] = "required|numeric";
        } else {
            $request["start_time"] = "required";
            $request["end_time"] = "required";
        }

        return $request;
    }

    public function attributes()
    {
        return [
            "name" => "nama",
            "year" => "tahun",
            "schedule" => "jadwal",
            "start_time" => "waktu mulai",
            "end_time" => "waktu selesai",
            "period" => "periode",
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
