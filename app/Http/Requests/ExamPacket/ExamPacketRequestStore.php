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
            "year" => "numeric",
            "exam_schedule" => "required",
            "close_schedule" => "required",
            "price" => "required",
            "account_number" => "required",
            "operator_id" => "required|exists:user_operators,uuid",
            "welder_skill_id" => "required|exists:welder_skills,uuid",
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
            "year" => "tahun",
            "exam_schedule" => "jadwal ujian",
            "start_time" => "waktu mulai",
            "end_time" => "waktu selesai",
            "period" => "periode",
            "close_schedule" => "jadwal penutupan pendaftaran",
            "person_responsible" => "penanggung jawab",
            "price" => "harga ujian",
            "account_number" => "nomor rekening",
            "welder_skill_id" => "skema uji kompetensi"
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
