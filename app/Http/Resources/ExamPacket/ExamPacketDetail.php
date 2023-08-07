<?php

namespace App\Http\Resources\ExamPacket;

use App\Http\Traits\MessageFixer;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamPacketDetail extends JsonResource
{
    use MessageFixer;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "competence_schema" => $this->competenceSchema,
            "year" => $this->year,
            "status" => $this->status,
            "exam_schedule"  => $this->exam_schedule,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "period" => $this->period,
            "uuid" => $this->uuid,
            "close_schedule" => $this->close_schedule,
            "operator" => $this->operator,
            "price" => $this->price,
            "account_number" => $this->account_number,
        ];

        if (auth()->user()->onlyRoles([User::ADMIN_APP, User::EXPERT, User::OPERATOR])) {
            $data["exams"] = $this->exams;
        }

        if (auth()->user()->isMemberWelder()) {
            $data["exam_packet_has_welder"] = $this->examPacketHasWelder()->welderAuth()->first();
        }

        return $data;
    }
}
