<?php

namespace App\Http\Controllers;

use App\Http\Facades\Firestore\FirestoreRepository;
use App\Http\Filters\WelderAnswer\ExamId;
use App\Http\Requests\WelderAnswer\WelderAnswerCreateRequest;
use App\Http\Resources\WelderAnswer\WelderAnswerCollection;
use App\Http\Resources\WelderAnswer\WelderAnswerCorrection;
use App\Http\Resources\WelderAnswer\WelderAnswerDetail;
use App\Http\Traits\MessageFixer;
use App\Models\Firebase;
use App\Models\WelderAnswer;
use App\Models\WelderHasExamPacket;
use App\Repositories\Answer\AnswerRepository;
use App\Repositories\Exam\ExamRepository;
use App\Repositories\ExamPacket\ExamPacketRepository;
use App\Repositories\WelderAnswer\WelderAnswerRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelderAnswerController extends Controller
{
    use MessageFixer;

    protected $answerRepository, $welderAnswerRepository, $examRepository, $examPacketRepository, $firestoreRepository;

    public function __construct(
        AnswerRepository $answerRepository,
        WelderAnswerRepository $welderAnswerRepository,
        ExamRepository $examRepository,
        ExamPacketRepository $examPacketRepository,
        FirestoreRepository $firestoreRepository

    ) {
        $this->answerRepository = $answerRepository;
        $this->welderAnswerRepository = $welderAnswerRepository;
        $this->examRepository = $examRepository;
        $this->examPacketRepository = $examPacketRepository;
        $this->firestoreRepository = $firestoreRepository;
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

    public function correctAnswer(string $examPacketId)
    {
        $examPacket = $this->examPacketRepository->findOrFail($examPacketId);

        $examPacket->evaluation = $examPacket->examPacketHasWelder()->where([
            "user_id" => auth()->user()->id
        ])->first();

        $examPacket->load(["competenceSchema", "operator"]);

        return new WelderAnswerCorrection($examPacket);
    }

    public function create()
    {
        //
    }

    public function store(WelderAnswerCreateRequest $request)
    {
        DB::beginTransaction();

        $welderAnswerId = null;
        $examPacket = null;

        $request->merge([
            "user_id" => Auth::user()->id,
            "exam_id" => Str::lower($request->exam_id)
        ]);

        $welderAnswer = $this->firestoreRepository->query(Firebase::WELDER_ANSWER)->where('user_id', '=', auth()->user()->id)->where('exam_id', '=', $request->exam_id)->documents();

        foreach ($welderAnswer as $answer) {
            $welderAnswerId = $answer->id();
        }

        if ($request->has('exam_packet_id')) {
            $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);
        }

        try {
            if (!$welderAnswerId) {
                $answer = $this->firestoreRepository->create(Firebase::WELDER_ANSWER, $request->except(['status', 'exam_packet_id']));
            } else {
                $answer = $this->firestoreRepository->update(Firebase::WELDER_ANSWER, $welderAnswerId, $request->except(['status', 'exam_packet_id']));
            }

            if ($request->has('status')) {
                $examPacket->examPacketHasWelder()->welderAuth()->update([
                    "status" => WelderHasExamPacket::FINISH,
                    "finished_at" => Carbon::now()
                ]);
            }

            DB::commit();
            return $this->successMessage("jawaban berhasil disimpan", $answer);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show(string $id)
    {
        $data = null;

        $welderAnswer = $this->firestoreRepository->query(Firebase::WELDER_ANSWER)->where('user_id', '=', auth()->user()->id)->where('exam_id', '=', $id)->documents();

        foreach ($welderAnswer as $answer) {
            $data = $answer->data();
        }

        return new WelderAnswerDetail($data);
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
