<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamPacket\ExamPacketRequestStore;
use App\Http\Resources\ExamPacket\ExamPacketCollection;
use App\Http\Resources\ExamPacket\ExamPacketDetail;
use App\Http\Traits\MessageFixer;
use App\Repositories\ExamPacket\ExamPacketRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExamPacketController extends Controller
{
    use MessageFixer;

    protected $examPacketRepository;

    public function __construct(ExamPacketRepository $examPacketRepository)
    {
        $this->examPacketRepository = $examPacketRepository;
    }

    public function index()
    {
        $examPackets = $this->examPacketRepository->all();

        return new ExamPacketCollection($examPackets);
    }

    public function store(ExamPacketRequestStore $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'uuid' => Str::uuid(),
                'year' => date("Y")
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

    public function destroy($id)
    {
        DB::beginTransaction();

        $examPacket = $this->examPacketRepository->findOrFail($id);

        try {
            $examPacket->delete();

            DB::commit();

            return $this->successMessage("data berhasil dihapus", $examPacket);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
