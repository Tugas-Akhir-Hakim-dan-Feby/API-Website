<?php

namespace App\Http\Controllers;

use App\Http\Filters\WelderHasExamPacket\ByExamPacketId;
use App\Http\Filters\WelderHasExamPacket\ByWelderId;
use App\Http\Filters\WelderHasExamPacket\Search;
use App\Http\Filters\WelderHasExamPacket\ShowByStatus;
use App\Http\Requests\ExamPacket\KeyCheckRequest;
use App\Http\Requests\ExamPacket\ValuePracticeRequest;
use App\Http\Resources\ExamPacket\GuestCollection;
use App\Http\Resources\ExamPacket\WelderHasExamPacketCollection;
use App\Http\Traits\MessageFixer;
use App\Imports\Util;
use App\Mail\SendDetailPacket;
use App\Models\WelderHasExamPacket;
use App\Repositories\ExamPacket\ExamPacketRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\WelderHasExamPacket\WelderHasExamPacketRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpWord\TemplateProcessor;

class WelderHasExamPacketController extends Controller
{
    use MessageFixer;

    protected $welderHasExamPacket, $examPacketRepository, $userRepository;

    public function __construct(
        WelderHasExamPacketRepository $welderHasExamPacket,
        ExamPacketRepository $examPacketRepository,
        UserRepository $userRepository
    ) {
        $this->welderHasExamPacket = $welderHasExamPacket;
        $this->examPacketRepository = $examPacketRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $examPackets = app(Pipeline::class)
            ->send($this->welderHasExamPacket->query())
            ->through([
                ByExamPacketId::class,
                Search::class,
                ByWelderId::class
            ])
            ->thenReturn()
            ->with(["examPacket.exam", "user", "examPacket.competenceSchema"])
            ->paginate($request->per_page);

        return new WelderHasExamPacketCollection($examPackets);
    }

    public function all(Request $request)
    {
        $examPackets = app(Pipeline::class)
            ->send($this->welderHasExamPacket->query())
            ->through([
                Search::class,
                ShowByStatus::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new GuestCollection($examPackets);
    }

    public function check($examPacketId)
    {
        $examPacket = $this->examPacketRepository->findOrFail($examPacketId);

        $welderExamPacket = $this->welderHasExamPacket->findByCriteria(["exam_packet_id" => $examPacket->id]);

        if (!$welderExamPacket) {
            return 2;
        }

        return 1;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            "document_payment" => "required|image|mimes:png,jpg,jpeg"
        ], [], [
            "document_payment" => "bukti pembayaran"
        ]);

        if ($validator->fails()) {
            return $this->warningMessage($validator->errors());
        }

        $keyPacket = Str::random(8);

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        try {
            $request->merge([
                "uuid" => Str::uuid(),
                "user_id" => auth()->user()->id,
                "exam_packet_id" => $examPacket->id,
                "key_packet" => Hash::make($keyPacket),
                "payment" => $request->file('document_payment')->store('payment_document')
            ]);

            $welderHasExamPacket = $this->welderHasExamPacket->create($request->all());
            $welderHasExamPacket = [
                "packet" => $examPacket,
                "user" => auth()->user(),
                "key_packet" => $keyPacket
            ];

            Mail::to(auth()->user()->email)->send(new SendDetailPacket($welderHasExamPacket));

            DB::commit();
            return $this->successMessage("register berhasil", $request);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function insertValuePactice(ValuePracticeRequest $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($request->user_id);
        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            "exam_packet_id" => $examPacket->id,
            "user_id" => $user->id
        ]);

        try {
            $welderHasExamPacket->update([
                "practice_value" => $request->value_practice
            ]);

            DB::commit();
            return $this->successMessage("nilai praktek berhasil disimpan", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function keyCheck(KeyCheckRequest $request)
    {
        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $welderExamPacket = $this->welderHasExamPacket->findByCriteria([
            ["exam_packet_id", $examPacket->id],
            ["user_id", auth()->user()->id]
        ]);

        if ($welderExamPacket->status == WelderHasExamPacket::PUNISHMENT) {
            return $this->warningMessage('ujian anda dibekukan, silahkan hubungi operator ujian!');
        }

        if (!Hash::check($request->key_packet, $welderExamPacket->key_packet)) {
            return $this->warningMessage('kunci paket salah!');
        }

        return $this->successMessage("kunci paket cocok!", []);
    }

    public function updatePenalty(Request $request)
    {
        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            ["exam_packet_id", $examPacket->id],
            ["user_id", auth()->user()->id]
        ]);

        try {
            $welderHasExamPacket->update([
                "penalty" => $welderHasExamPacket->penalty - 1
            ]);

            DB::commit();
            return $this->successMessage("nilai praktek berhasil disimpan", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateKeyPacket(Request $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($request->user_id);
        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            "exam_packet_id" => $examPacket->id,
            "user_id" => $user->id
        ]);

        try {
            $welderHasExamPacket->update([
                "key_packet" => Hash::make($request->key_packet),
                "status" => 0
            ]);

            DB::commit();
            return $this->successMessage("kunci paket berhasil diperbaharui", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function paymentValidate(Request $request, $id)
    {
        DB::beginTransaction();

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            "uuid" => $id,
        ]);

        try {
            $welderHasExamPacket->validated_at = Carbon::now();
            $welderHasExamPacket->save();

            DB::commit();
            return $this->successMessage("validasi berhasil diperbaharui", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function punishment(Request $request)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            ["exam_packet_id", $examPacket->id],
            ["user_id", auth()->user()->id]
        ]);

        try {
            $welderHasExamPacket->update([
                "key_packet" => Str::random(6),
                "status" => WelderHasExamPacket::PUNISHMENT
            ]);

            DB::commit();
            return $this->successMessage("pembekuan ujian berlaku", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateEvaluation(Request $request, $id)
    {
        DB::beginTransaction();

        $welderHasExamPacket = $this->welderHasExamPacket->findByCriteria([
            "uuid" => $id
        ]);

        try {
            $welderHasExamPacket->grade = $request->grade;
            $welderHasExamPacket->notes = $request->notes;
            $welderHasExamPacket->status = $request->status;

            if ($request->status == 3) {
                $welderHasExamPacket->certificate_number = $this->generateAbbreviation($welderHasExamPacket->examPacket->competenceSchema->skill_name);
            } else {
                $welderHasExamPacket->certificate_number = null;
            }

            $welderHasExamPacket->save();

            DB::commit();
            return $this->successMessage("penilaian berhasil disimpan", $welderHasExamPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function downloadCertificate(string $id)
    {
        $welderAnswer = $this->welderHasExamPacket->findByCriteria(["uuid" => $id])->first();

        $template = new TemplateProcessor("./storage/" . $welderAnswer->examPacket->certificate);
        $template->setValues([
            "participant_name" => $welderAnswer->user->name,
            "scheme" => $welderAnswer->examPacket->competenceSchema->skill_name,
        ]);

        $template->setImageValue("logo", str_replace(url('/') . '/', '', $welderAnswer->examPacket->operator->logo->document_path));


        $template->saveAs("archives/certificates/" . Str::slug($welderAnswer->examPacket->competenceSchema->skill_name, '_') . Str::slug($welderAnswer->user->name, '_') . ".docx");

        return response()->download("archives/certificates/" . Str::slug($welderAnswer->examPacket->competenceSchema->skill_name, '_') . Str::slug($welderAnswer->user->name, '_') . ".docx");
    }

    public function generateAbbreviation($fullName)
    {
        $words = explode(' ', $fullName);
        $abbreviation = '';

        foreach ($words as $word) {
            $abbreviation .= strtoupper($word[0]);
        }

        return $abbreviation  . '_' . mt_rand(1000000000, 9999999999);
    }
}
