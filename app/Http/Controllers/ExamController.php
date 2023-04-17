<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\ExamRequest;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamDetail;
use App\Http\Traits\MessageFixer;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\ExamPacket\ExamPacketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    use MessageFixer;

    protected $examRepository, $examPacketRepository;

    public function __construct(ExamRepository $examRepository, ExamPacketRepository $examPacketRepository)
    {
        $this->examRepository = $examRepository;
        $this->examPacketRepository = $examPacketRepository;
    }

    public function index()
    {
        $exams = $this->examRepository->all();

        return new ExamCollection($exams);
    }

    public function store(ExamRequest $request)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        try {
            $exam = $examPacket->exams()->create([
                "uuid" => Str::uuid(),
                "question" => $request->question
            ]);

            foreach ($request->answers as $answer) {
                $exam->answers()->create([
                    "uuid" => Str::uuid(),
                    "answer" => $answer
                ]);
            }

            $correctAnswer = $exam->answers()->where("answer", $request->correct_answer)->first();

            $exam->update([
                "answer_id" => $correctAnswer->id
            ]);

            DB::commit();
            return $this->successMessage("data berhasil ditambahkan", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $exam = $this->examRepository->findOrFail($id);
        $exam->load(["answers", "correctAnswer"]);

        return new ExamDetail($exam);
    }

    public function update(ExamRequest $request, $id)
    {
        DB::beginTransaction();

        $exam = $this->examRepository->findOrFail($id);

        try {
            foreach ($exam->answers as $key => $answer) {
                $answer->update([
                    "answer" => $request->answers[$key]
                ]);
            }

            $correctAnswer = $exam->answers()->where("answer", $request->correct_answer)->first();

            $exam->update([
                "question" => $request->question,
                "answer_id" => $correctAnswer->id
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $exam = $this->examRepository->findOrFail($id);

        try {
            if ($exam->answers) {
                $exam->answers()->delete();
            }

            $exam->delete();

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
