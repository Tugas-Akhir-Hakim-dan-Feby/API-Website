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
use App\Imports\ExamImport;
use App\Models\Exam;
use App\Models\Firebase;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\ExamPacket\ExamPacketRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Contract\Firestore;
use Kreait\Firebase\Factory;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx',
            'exam_packet_id' => 'required|exists:exam_packets,uuid'
        ]);

        if ($validator->fails()) {
            return $this->warningMessage($validator->errors());
        }

        try {
            DB::commit();

            Excel::import(new ExamImport, $request->file('file'));

            return $this->successMessage('data berhasil ditambahkan', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function store(ExamRequest $request)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $request->merge([
            "exam_packet_id" => $examPacket->id,
            "uuid" => Str::random(12),
        ]);

        try {
            if ($request->hasFile('file')) {
                $examFile = $request->file('file')->store('exam_files');
                $request->merge([
                    "document" => url('storage/' . $examFile)
                ]);
            }

            $firestore = $this->firestoreRepsitory->create(Firebase::EXAMS, $request->except(['correct_answer', 'file']));

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
        $id = Str::lower($id);
        $data = null;

        $exam = $this->firestoreRepsitory->show(Firebase::EXAMS, $id);
        $answers = $this->firestoreRepsitory->query(Firebase::ANSWERS)->where('uuid', '=', $exam['uuid'])->documents();

        foreach ($answers as $index => $answer) {
            $data = $answer->data();
        }

        $exam["correct_answer"] = $data;

        return new ExamDetail($exam);
    }

    public function update(ExamRequest $request, $id)
    {
        DB::beginTransaction();

        $id = Str::lower($id);
        $correctAnswerId = null;

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $request->merge([
            "exam_packet_id" => $examPacket->id,
        ]);

        $exam = $this->firestoreRepsitory->show(Firebase::EXAMS, $id);
        $answers = $this->firestoreRepsitory->query(Firebase::ANSWERS)->where('uuid', '=', $exam['uuid'])->documents();

        foreach ($answers as $key => $answer) {
            $correctAnswerId = $answer->id();
        }

        try {
            $this->firestoreRepsitory->update(Firebase::EXAMS, $id, $request->except(['correct_answer', 'file']));

            return $this->successMessage("data berhasil diperbaharui", $exam);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        $id = Str::lower($id);

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
