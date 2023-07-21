<?php

namespace App\Http\Controllers;

use App\Http\Filters\ExamPacket\CheckSchedule;
use App\Http\Filters\ExamPacket\Search;
use App\Http\Filters\ExamPacket\ShowByExpert;
use App\Http\Filters\ExamPacket\ShowByOperator;
use App\Http\Filters\ExamPacket\Sort;
use App\Http\Requests\ExamPacket\ExamPacketRequestStore;
use App\Http\Requests\ExamPacket\UpdateCertificateRequest;
use App\Http\Resources\ExamPacket\ExamPacketCollection;
use App\Http\Resources\ExamPacket\ExamPacketDetail;
use App\Http\Traits\MessageFixer;
use App\Models\ExamPacket;
use App\Repositories\ExamPacket\ExamPacketRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserOperator\UserOperatorRepository;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExamPacketController extends Controller
{
    use MessageFixer;

    protected $examPacketRepository, $userRepository, $operatorRepository, $welderSkillRepository;

    public function __construct(ExamPacketRepository $examPacketRepository, UserRepository $userRepository, UserOperatorRepository $operatorRepository, WelderSkillRepository $welderSkillRepository)
    {
        $this->examPacketRepository = $examPacketRepository;
        $this->userRepository = $userRepository;
        $this->operatorRepository = $operatorRepository;
        $this->welderSkillRepository = $welderSkillRepository;
    }

    public function index(Request $request)
    {
        $examPackets = app(Pipeline::class)
            ->send($this->examPacketRepository->query())
            ->through([
                Search::class,
                Sort::class,
                CheckSchedule::class,
                ShowByOperator::class
            ])
            ->thenReturn()
            ->with(["exam", "competenceSchema", "examPacketHasWelders", "operator.logo"])
            ->paginate($request->per_page);

        return new ExamPacketCollection($examPackets);
    }

    public function store(ExamPacketRequestStore $request)
    {
        DB::beginTransaction();

        $operator = $this->operatorRepository->findOrFail($request->operator_id);
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        try {
            $request->merge([
                'certificate' => $request->file('document_certificate')->store('certificate')
            ]);

            $request->merge([
                'operator_id' => $operator->id,
                'uuid' => Str::uuid(),
                'year' => date("Y"),
                'user_id' => $operator->user_id,
                'welder_skill_id' => $welderSkill->id,
            ]);

            $examPacket = $this->examPacketRepository->create($request->all());

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
        $examPacket->load(["exams", "examPacketHasWelder", "competenceSchema", "operator.logo"]);

        return new ExamPacketDetail($examPacket);
    }

    public function update(ExamPacketRequestStore $request, $id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);
        $operator = $this->operatorRepository->findOrFail($request->operator_id);
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            'operator_id' => $operator->id,
            'user_id' => $operator->user_id,
            'welder_skill_id' => $welderSkill->id
        ]);

        try {
            $examPacket->update($request->all());

            DB::commit();

            return $this->successMessage("data berhasil diperbaharui", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateCertificate(UpdateCertificateRequest $request, $id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);

        try {
            if ($examPacket->certificate) {
                $path = str_replace(url('storage') . '/', '', $examPacket->certificate);
                Storage::delete($path);
            }

            $examPacket->update([
                'certificate' => $request->file('document_certificate')->store('certificate')
            ]);

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
