<?php

namespace App\Imports;

use App\Models\ExamPacket;
use App\Models\User;
use App\Models\WelderHasExamPacket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EvaluationImport implements ToCollection, WithHeadingRow
{
    use SkipsFailures;

    protected $examPacket;

    public function __construct(ExamPacket $examPacket)
    {
        $this->examPacket = $examPacket;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $collect) {
            $user = User::whereEmail($collect["email"])->first();

            $welderHasExamPacket = WelderHasExamPacket::where([
                "user_id" => $user->id,
                "exam_packet_id" => $this->examPacket->id
            ])->first();

            $welderHasExamPacket->grade = nl2br($collect["penilaian"]);
            $welderHasExamPacket->status = $collect["sertifikat"] == "Ya" ? 3 : 1;
            $welderHasExamPacket->notes = $collect["catatan"];

            $welderHasExamPacket->save();
        }
    }
}
