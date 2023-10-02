<?php

namespace App\Imports;

use App\Http\Facades\Firestore\FirestoreRepository;
use App\Models\ExamPacket;
use App\Models\Firebase;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $data = [];

        foreach ($collection as $collect) {
            $collecting["question"] = $collect["question"];
            $collecting["answer_type"] = $collect["answer_type"];
            $collecting["correct_answer"] = $collect["correct_answer"];
            $collecting["uuid"] = Str::random(12);
            for ($i = 0; $i < 5; $i++) {
                $collecting["answers"][$i] = $collect["answers$i"];
            }

            $data[] = $collecting;
        }

        $filteredData = array_map(function ($item) {
            $item["answers"] = array_filter($item["answers"], function ($answer) {
                return $answer !== null;
            });

            return $item;
        }, $data);

        $examPacket = ExamPacket::where('uuid', request()->exam_packet_id)->first();
        $firestoreRepository = new FirestoreRepository();
        foreach ($filteredData as $filter) {
            $firestoreRepository->create(Firebase::EXAMS, [
                "question" => $filter["question"],
                "answer_type" => $filter["answer_type"],
                "uuid" => $filter["uuid"],
                "answers" => $filter["answers"],
                "exam_packet_id" => $examPacket->id,
            ]);

            $firestoreRepository->create(Firebase::ANSWERS, [
                "uuid" => $filter["uuid"],
                "correct_answer" => $filter["correct_answer"],
            ]);
        }
    }
}
