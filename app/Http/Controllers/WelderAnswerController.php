<?php

namespace App\Http\Controllers;

use App\Http\Filters\WelderAnswer\ExamId;
use App\Http\Requests\WelderAnswer\WelderAnswerCreateRequest;
use App\Http\Resources\WelderAnswer\WelderAnswerCollection;
use App\Http\Resources\WelderAnswer\WelderAnswerDetail;
use App\Http\Traits\MessageFixer;
use App\Models\WelderAnswer;
use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\WelderAnswer\WelderAnswerRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelderAnswerController extends Controller
{
    use MessageFixer;

    protected $answerRepository, $welderAnswerRepository, $examRepository;

    public function __construct(
        AnswerRepository $answerRepository,
        WelderAnswerRepository $welderAnswerRepository,
        ExamRepository $examRepository
    ) {
        $this->answerRepository = $answerRepository;
        $this->welderAnswerRepository = $welderAnswerRepository;
        $this->examRepository = $examRepository;
    }

    public function index(Request $request)
    {
        $examPackets = app(Pipeline::class)
            ->send($this->welderAnswerRepository->query())
            ->through([
                ExamId::class
            ])
            ->thenReturn()->get();

        return new WelderAnswerCollection($examPackets);
    }

    public function create()
    {
        //
    }

    public function store(WelderAnswerCreateRequest $request)
    {
        DB::beginTransaction();

        $answer = $this->answerRepository->where([["uuid", $request->answer_id]])->first();
        $exam = $this->examRepository->where([["uuid", $request->exam_id]])->first();

        $request->merge([
            "user_id" => Auth::user()->id,
            "answer_id" => $answer->id,
            "exam_id" => $exam->id,
            "uuid" => Str::uuid()
        ]);

        try {
            $welderAnswer = $this->welderAnswerRepository->where([
                "user_id" => Auth::user()->id, "exam_id" => $exam->id,
            ])->first();

            if ($welderAnswer) {
                $welderAnswer->update([
                    "answer_id" => $answer->id
                ]);
            } else {
                $this->welderAnswerRepository->create($request->all());
            }

            DB::commit();
            return $this->successMessage("jawaban berhasil disimpan", $answer);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show(string $welderAnswer)
    {
        $welderAnswer = $this->welderAnswerRepository->where(["uuid" => $welderAnswer])->first();

        return new WelderAnswerDetail($welderAnswer);
    }

    public function edit(WelderAnswer $welderAnswer)
    {
        //
    }

    public function update(Request $request, WelderAnswer $welderAnswer)
    {
        //
    }

    public function destroy(WelderAnswer $welderAnswer)
    {
        //
    }
}
