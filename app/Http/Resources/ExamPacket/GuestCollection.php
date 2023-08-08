<?php

namespace App\Http\Resources\ExamPacket;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GuestCollection extends ResourceCollection
{
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
            $examPacket->examPacket->load(["operator", "competenceSchema"]);

            $data[] = [
                "uuid" => $examPacket->uuid,
                "certificate_number" => $examPacket->certificate_number,
                "exam_packet" => $examPacket->examPacket,
                "user" => $examPacket->user
            ];
        }

        return $data;
    }
}
