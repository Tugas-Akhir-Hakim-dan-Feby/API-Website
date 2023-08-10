<?php

namespace App\Http\Resources\ExamPacket;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WelderHasExamPacketCollection extends ResourceCollection
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
            $examPacket->examPacket->load(["competenceSchema"]);

            $data[] = [
                "uuid" => $examPacket->uuid,
                "certificate_number" => $examPacket->certificate_number,
                "exam_packet" => $examPacket->examPacket,
                "user" => $examPacket->user,
                "penalty" => $examPacket->penalty,
                "grade" => $examPacket->grade,
                "status" => $examPacket->status,
                "payment" => $examPacket->payment,
                "notes" => $examPacket->notes,
                "correct_answer" => $this->getCorrectAnswer($examPacket->examPacket->exams),
                "wrong_answer" => $this->getWrongAnswer($examPacket->examPacket->exams),
                "correct_precentage" => $this->getCorrectPrecentage($examPacket->examPacket->exams),
                "wrong_precentage" => $this->getWrongPrecentage($examPacket->examPacket->exams),
                "created_at" => $examPacket->created_at,
                "updated_at" => $examPacket->updated_at,
                "validated_at" => $examPacket->validated_at,
            ];
        }

        return $data;
    }

    protected function getCorrectAnswer($exams)
    {
        $value = 0;

        foreach ($exams as $exam) {
            if ($exam->welderAnswer && $exam->welderAnswer->answer_id == $exam->answer_id) {
                $value++;
            }
        }

        return $value;
    }

    protected function getWrongAnswer($exams)
    {
        $value = 0;

        foreach ($exams as $exam) {
            if ($exam->welderAnswer && $exam->welderAnswer?->answer_id != $exam->answer_id) {
                $value++;
            }
        }

        return $value;
    }

    protected function getCorrectPrecentage($exams)
    {
        $total = $this->getCorrectAnswer($exams) + $this->getWrongAnswer($exams);

        if ($this->getCorrectAnswer($exams) != 0) {
            $precentage = ($this->getCorrectAnswer($exams) / $total) * 100;
        } else {
            $precentage = 0;
        }

        return $precentage;
    }

    protected function getWrongPrecentage($exams)
    {
        $total = $this->getCorrectAnswer($exams) + $this->getWrongAnswer($exams);

        if ($this->getWrongAnswer($exams) != 0) {
            $precentage = ($this->getWrongAnswer($exams) / $total) * 100;
        } else {
            $precentage = 0;
        }

        return $precentage;
    }
}
