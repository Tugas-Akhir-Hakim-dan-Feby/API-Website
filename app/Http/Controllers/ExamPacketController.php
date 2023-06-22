<?php

namespace App\Http\Controllers;

use App\Http\Filters\ExamPacket\CheckSchedule;
use App\Http\Filters\ExamPacket\Search;
use App\Http\Filters\ExamPacket\ShowByExpert;
use App\Http\Filters\ExamPacket\Sort;
use App\Http\Filters\ExamPacket\SortByUser;
use App\Http\Requests\ExamPacket\ExamPacketRequestStore;
use App\Http\Resources\ExamPacket\ExamPacketCollection;
use App\Http\Resources\ExamPacket\ExamPacketDetail;
use App\Http\Traits\MessageFixer;
use App\Models\ExamPacket;
use App\Repositories\ExamPacket\ExamPacketRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExamPacketController extends Controller
{
    use MessageFixer;

    protected $examPacketRepository, $userRepository;

    public function __construct(ExamPacketRepository $examPacketRepository, UserRepository $userRepository)
    {
        $this->examPacketRepository = $examPacketRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $examPackets = app(Pipeline::class)
            ->send($this->examPacketRepository->query())
            ->through([
                Search::class,
                Sort::class,
                CheckSchedule::class,
                ShowByExpert::class
            ])
            ->thenReturn()
            ->with(["exam", "examPacketHasWelders"])
            ->paginate($request->per_page);

        return new ExamPacketCollection($examPackets);
    }

    public function store(ExamPacketRequestStore $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'uuid' => Str::uuid(),
                'year' => date("Y"),
                'user_id' => auth()->user()->id
            ]);

            $examPacket = $this->examPacketRepository->create($request->all());

            $examPacket->examPacketHasExperts()->create([
                'uuid' => Str::uuid(),
                "user_id" => auth()->user()->id
            ]);

            foreach ($request->person_responsible as $userId) {
                $user = $this->userRepository->findOrFail($userId);
                $examPacket->examPacketHasExperts()->create([
                    'uuid' => Str::uuid(),
                    "user_id" => $user->id
                ]);
            }

            DB::commit();

            return $this->successMessage("data berhasil ditambahkan", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $examPacket = $this->examPacketRepository->findOrFail($id);
        $examPacket->load(["exams", "examPacketHasWelder"]);

        return new ExamPacketDetail($examPacket);
    }

    public function update(ExamPacketRequestStore $request, $id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);

        try {
            $examPacket->update($request->all());

            DB::commit();

            return $this->successMessage("data berhasil diperbaharui", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);

        try {
            $examPacket->update([
                'status' => $examPacket->status ? ExamPacket::INACTIVE : ExamPacket::ACTIVE
            ]);

            DB::commit();

            return $this->successMessage("data berhasil diperbaharui", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);

        try {
            if ($examPacket->exams) {
                foreach ($examPacket->exams as $exam) {
                    $exam->answers()->delete();
                }

                $examPacket->exams()->delete();
            }

            if ($examPacket->examPacketHasExperts) {
                $examPacket->examPacketHasExperts()->delete();
            }

            $examPacket->delete();

            DB::commit();

            return $this->successMessage("data berhasil dihapus", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
