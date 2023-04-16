<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamPacket\ExamPacketRequestStore;
use App\Http\Resources\ExamPacket\ExamPacketCollection;
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
