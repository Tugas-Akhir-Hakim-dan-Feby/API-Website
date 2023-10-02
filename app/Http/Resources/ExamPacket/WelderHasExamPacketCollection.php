<?php

namespace App\Http\Resources\ExamPacket;

use App\Http\Facades\Firestore\FirestoreRepository;
use App\Models\Firebase;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WelderHasExamPacketCollection extends ResourceCollection
{
    protected $correctAnswer, $wrongAnswer;

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
                "correct_answer" => $this->getCorrectAnswer($examPacket->examPacket, $examPacket->user),
                "wrong_answer" => $this->getWrongAnswer($examPacket->examPacket, $examPacket->user),
                "correct_precentage" => $this->getCorrectPrecentage($examPacket->examPacket, $examPacket->user),
                "wrong_precentage" => $this->getWrongPrecentage($examPacket->examPacket, $examPacket->user),
                "created_at" => $examPacket->created_at,
                "updated_at" => $examPacket->updated_at,
                "validated_at" => $examPacket->validated_at,
                "finished_at" => $examPacket->finished_at,
            ];
        }

        return $data;
    }

    protected function getCorrectAnswer($examPacket, $user)
    {
        $value = 0;
        $dataAnswer = null;

        $firestoreRepository = new FirestoreRepository();
        $exams = $firestoreRepository->query(Firebase::EXAMS)
            ->where('exam_packet_id', '=', $examPacket->id)->documents();

        foreach ($exams as $exam) {
            $welderAnswers = $firestoreRepository->query(Firebase::WELDER_ANSWER)
                ->where('exam_id', '=', $exam->id())
                ->where('user_id', '=', $user->id)
                ->documents();
            $answers = $firestoreRepository->query(Firebase::ANSWERS)->where('uuid', '=', $exam->data()["uuid"])->documents();

            foreach ($answers as $answer) {
                $dataAnswer = $answer->data();
            }

            foreach ($welderAnswers as $welderAnswer) {
                if ($welderAnswer->data()["answer_id"] == $dataAnswer["correct_answer"]) {
                    $value += 1;
                }
            }
        }

        $this->correctAnswer = $value;
        return $value;
    }

    protected function getWrongAnswer($examPacket, $user)
    {
        $value = 0;
        $dataAnswer = null;

        $firestoreRepository = new FirestoreRepository();
        $exams = $firestoreRepository->query(Firebase::EXAMS)
            ->where('exam_packet_id', '=', $examPacket->id)
            ->documents();

        foreach ($exams as $exam) {
            $welderAnswers = $firestoreRepository->query(Firebase::WELDER_ANSWER)->where('exam_id', '=', $exam->id())->where('user_id', '=', $user->id)->documents();
            $answers = $firestoreRepository->query(Firebase::ANSWERS)->where('uuid', '=', $exam->data()["uuid"])->documents();

            foreach ($answers as $answer) {
                $dataAnswer = $answer->data();
            }

            foreach ($welderAnswers as $welderAnswer) {
                if ($welderAnswer->data()["answer_id"] != $dataAnswer["correct_answer"]) {
                    $value += 1;
                }
            }
        }

        $this->wrongAnswer = $value;
        return $value;
    }

    protected function getCorrectPrecentage($examPacket, $user)
    {
        $total = $this->correctAnswer + $this->wrongAnswer;

        if ($this->correctAnswer != 0) {
            $precentage = ($this->correctAnswer / $total) * 100;
        } else {
            $precentage = 0;
        }

        return number_format($precentage, 2);
    }

    protected function getWrongPrecentage($examPacket, $user)
    {
        $total = $this->correctAnswer + $this->wrongAnswer;

        if ($this->wrongAnswer != 0) {
            $precentage = ($this->wrongAnswer / $total) * 100;
        } else {
            $precentage = 0;
        }

        return number_format($precentage, 2);
    }
}
