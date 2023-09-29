<?php

namespace App\Http\Controllers;

use App\Http\Facades\Firestore\FirestoreRepository;
use App\Http\Facades\PaginationHelper;
use App\Http\Filters\Exam\ByExamPacketId;
use App\Http\Filters\Exam\Search;
use App\Http\Requests\Exam\ExamRequest;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Exam;
use App\Models\Firebase;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\ExamPacket\ExamPacketRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Factory;

class ExamController extends Controller
{
    use MessageFixer;

    protected $firestoreRepsitory, $examPacketRepository;

    public function __construct(
        FirestoreRepository $firestoreRepsitory,
        ExamPacketRepository $examPacketRepository,
        Firestore $firestore
    ) {
        $this->firestoreRepsitory = $firestoreRepsitory;
        $this->examPacketRepository = $examPacketRepository;
    }

    public function index(Request $request)
    {
        $perPage = 15;
        $firestore = $this->firestoreRepsitory->query(Firebase::EXAMS);

        if ($request->exam_packet_id) {
            $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);
            $firestore = $firestore->where('exam_packet_id', '=', $examPacket->id);
        }

        if ($request->per_page) {
            $perPage = $request->per_page;
        }

        $firestore = $firestore->documents();

        $data = [];

        foreach ($firestore as $index => $exam) {
            $data[$exam->id()] = $exam->data();
        }

        return new ExamCollection(PaginationHelper::paginate(collect($data), $perPage));
    }

    public function store(ExamRequest $request)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);
        $request->merge([
            "exam_packet_id" => $examPacket->id,
            "uuid" => Str::random(12)
        ]);

        try {
            $firestore = $this->firestoreRepsitory->create(Firebase::EXAMS, $request->except(['correct_answer']));

            $this->firestoreRepsitory->create(Firebase::ANSWERS, [
                "correct_answer" => $request->correct_answer,
                "uuid" => $request->uuid
            ]);

            return $this->successMessage("data berhasil ditambahkan", $firestore);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $exam = $this->firestoreRepsitory->findOrFail($id);
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

        $exam = $this->firestoreRepsitory->findOrFail($id);

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

            return $this->successMessage("data berhasil diperbaharui", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $exam = $this->firestoreRepsitory->show(Firebase::EXAMS, $id);
            $answers = $this->firestoreRepsitory->query(Firebase::ANSWERS)->where('uuid', '=', $exam['uuid'])->documents();

            foreach ($answers as $index => $answer) {
                $this->firestoreRepsitory->delete(Firebase::ANSWERS, $answer->id());
            }

            $this->firestoreRepsitory->delete(Firebase::EXAMS, $id);

            return $this->successMessage("data berhasil dihapus", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
