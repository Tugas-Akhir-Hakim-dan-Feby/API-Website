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
            "name" => $this->name,
            "year" => $this->year,
            "status" => $this->status,
            "schedule"  => $this->schedule,
            "start_time" => $this->start_time,
            "end_time" => $this->end_time,
            "period" => $this->period,
            "practice_exam_address" => $this->practice_exam_address,
            "uuid" => $this->uuid,
        ];

        if (auth()->user()->onlyRoles([User::ADMIN_APP, User::EXPERT])) {
            $data["exams"] = $this->exams;
        }

        if (auth()->user()->isMemberWelder()) {
            $data["exam_packet_has_welder"] = $this->examPacketHasWelder()->welderAuth()->first();
        }

        return $data;
    }
}
