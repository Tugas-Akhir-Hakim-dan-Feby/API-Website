<?php

namespace App\Http\Requests\Exam;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ExamRequest extends FormRequest
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
            "exam_packet_id" => "required|exists:exam_packets,uuid",
            "question" => "required",
            "answers" => "required|array",
            "answers.*" => "required",
            "file" => "file|image|mimes:png,jpg,jpeg,pdf,mp4,mkv,xlsx,docx",
        ];
    }

    public function attributes()
    {
        return [
            "exam_packet_id" => "paket pertanyaan",
            "question" => "pertanyaan",
            "answers" => "jawaban",
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
