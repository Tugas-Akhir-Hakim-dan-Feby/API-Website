<?php

namespace App\Http\Controllers;

use App\Http\Filters\WelderHasExamPacket\ByExamPacketId;
use App\Http\Filters\WelderHasExamPacket\Search;
use App\Http\Requests\ExamPacket\KeyCheckRequest;
use App\Http\Requests\ExamPacket\ValuePracticeRequest;
use App\Http\Resources\ExamPacket\WelderHasExamPacketCollection;
use App\Http\Traits\MessageFixer;
use App\Mail\SendDetailPacket;
use App\Models\WelderHasExamPacket;
use App\Repositories\ExamPacket\ExamPacketRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\WelderHasExamPacket\WelderHasExamPacketRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
                Search::class
            ])
            ->thenReturn()
            ->with(["examPacket.exam", "user"])
            ->paginate($request->per_page);

        return new WelderHasExamPacketCollection($examPackets);
    }

    public function check($examPacketId)
    {
        $examPacket = $this->examPacketRepository->findOrFail($examPacketId);

        $welderExamPacket = $this->welderHasExamPacket->findByCriteria([["exam_packet_id", $examPacket->id]]);

        if (!$welderExamPacket) {
            return false;
        }

        return true;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $keyPacket = Str::random(8);

        $examPacket = $this->examPacketRepository->findOrFail($request->exam_packet_id);

        $request->merge([
            "uuid" => Str::uuid(),
            "user_id" => auth()->user()->id,
            "exam_packet_id" => $examPacket->id,
            "key_packet" => Hash::make($keyPacket)
        ]);

        try {
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

        if (!Hash::check($request->key_packet, $welderExamPacket->key_packet)) {
            return $this->warningMessage('kunci paket salah!');
        }

        return $this->successMessage("kunci paket cocok!", []);
    }
}
