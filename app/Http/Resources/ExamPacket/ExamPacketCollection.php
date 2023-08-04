<?php

namespace App\Http\Resources\ExamPacket;

use App\Http\Traits\MessageFixer;
use App\Models\User;
use App\Models\WelderHasExamPacket;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ExamPacketCollection extends ResourceCollection
{
    use MessageFixer;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this as $examPacket) {
            $examPacketData = [
                "close_schedule" => $examPacket->close_schedule,
                "competence_schema" => $examPacket->competenceSchema,
                "end_time" => $examPacket->end_time,
                "exam" => $examPacket->exam,
                "exam_packet_has_welder" => $examPacket->examPacketHasWelder,
                "exam_schedule" => $examPacket->exam_schedule,
                "operator" => $examPacket->operator,
                "price" => $examPacket->price,
                "start_time" => $examPacket->start_time,
                "status" => $examPacket->status,
                "updated_at" => $examPacket->updated_at,
                "user" => $examPacket->user,
                "uuid" => $examPacket->uuid,
                "year" => $examPacket->year,
                "created_at" => $examPacket->created_at,
                "user_registered" => false
            ];

            if (auth()->user()->onlyRoles([User::MEMBER_APPLICATION, User::MEMBER_INDIVIDUAL])) {
                foreach ($examPacket->examPacketHasWelders as $examPacketHasWelder) {
                    if ($examPacketHasWelder->user_id == auth()->user()->id) {
                        $examPacketData["user_registered"] = true;
                    }
                }
            }


            $data[] = $examPacketData;
        }

        return $data;
        // return [
        //     "status" => "SUCCESS",
        //     "message" => "ambil data berhasil",
        //     "status_code" => Response::HTTP_OK,
        //     "data" => $this->collection,
        // ];
    }
}
