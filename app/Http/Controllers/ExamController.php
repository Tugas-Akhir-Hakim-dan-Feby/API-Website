<?php

namespace App\Http\Controllers;

use App\Http\Filters\Exam\ByExamPacketId;
use App\Http\Filters\Exam\Search;
use App\Http\Requests\Exam\ExamRequest;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Exam;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\ExamPacket\ExamPacketRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
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

    public function index(Request $request)
    {
        $exams = app(Pipeline::class)
            ->send($this->examRepository->query())
            ->through([
                Search::class,
                ByExamPacketId::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new ExamCollection($exams);
    }

    public function store(ExamRequest $request)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        try {
            $exam = $examPacket->exams()->create([
                "uuid" => Str::uuid(),
                "question" => $request->question,
                "type" => $request->answer_type
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
        $exam->load(["answers", "welderAnswer.answer"]);

        if (
            auth()->user()->isAdminApp() ||
            auth()->user()->isAdminHub() ||
            auth()->user()->isExpert()
        ) {
            $exam->load(["correctAnswer"]);
        }

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
                "answer_id" => $correctAnswer->id,
                "question" => $request->question
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
