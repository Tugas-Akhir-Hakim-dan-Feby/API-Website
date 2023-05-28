<?php

namespace App\Http\Resources\WelderAnswer;

use Illuminate\Http\Resources\Json\JsonResource;

class WelderAnswerCorrection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "schedule" => $this->schedule,
            "correct_answer" => $this->getCorrectAnswer(),
            "wrong_answer" => $this->getWrongAnswer(),
            "correct_precentage" => $this->getCorrectPrecentage(),
            "wrong_precentage" => $this->getWrongPrecentage(),
            "status" => "LULUS"
        ];
    }

    protected function getCorrectAnswer()
    {
        $value = 0;

        foreach ($this->exams as $exam) {
            if ($exam->welderAnswer->answer_id == $exam->answer_id) {
                $value++;
            }
        }

        return $value;
    }

    protected function getWrongAnswer()
    {
        $value = 0;

        foreach ($this->exams as $exam) {
            if ($exam->welderAnswer->answer_id != $exam->answer_id) {
                $value++;
            }
        }

        return $value;
    }

    protected function getCorrectPrecentage()
    {
        $total = $this->getCorrectAnswer() + $this->getWrongAnswer();

        $precentage = ($this->getCorrectAnswer() / $total) * 100;

        return $precentage;
    }

    protected function getWrongPrecentage()
    {
        $total = $this->getCorrectAnswer() + $this->getWrongAnswer();

        $precentage = ($this->getWrongAnswer() / $total) * 100;

        return $precentage;
    }
}