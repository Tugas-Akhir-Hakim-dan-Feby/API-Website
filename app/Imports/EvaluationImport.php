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

            $certificateNumber = Util::generateAbbreviation($welderHasExamPacket->examPacket->competenceSchema->skill_name);

            $welderHasExamPacket->grade = nl2br($collect["penilaian"]);
            $welderHasExamPacket->status = $collect["sertifikat"] == "Ya" ? 3 : 1;
            $welderHasExamPacket->notes = $collect["catatan"];

            if (!$welderHasExamPacket->certificate_number) {
                $welderHasExamPacket->certificate_number = $collect["sertifikat"] == "Ya" ? $certificateNumber : null;
            }

            $welderHasExamPacket->save();
        }
    }
}

class Util
{
    public static function generateAbbreviation($fullName)
    {
        $words = explode(' ', $fullName);
        $abbreviation = '';

        foreach ($words as $word) {
            $abbreviation .= strtoupper($word[0]);
        }

        return $abbreviation  . '_' . mt_rand(1000000000, 9999999999);
    }
}
