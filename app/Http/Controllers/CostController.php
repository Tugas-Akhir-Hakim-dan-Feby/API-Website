<?php

namespace App\Http\Controllers;

use App\Http\Resources\Cost\CostCollection;
use App\Http\Traits\MessageFixer;
use App\Models\Cost;
use App\Repositories\Cost\CostRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CostController extends Controller
{
    use MessageFixer;

    protected $costRepository;

    public function __construct(CostRepository $costRepository)
    {
        $this->costRepository = $costRepository;
    }

    public function index()
    {
        $costs = $this->costRepository->all();

        return new CostCollection($costs);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'nominal_price' => 'required|numeric'
        ], [], [
            'nominal_price' => 'nominal harga'
        ]);

        if ($validator->fails()) {
            $response = new JsonResponse([
                'status' => 'WARNING',
                'messages' => $validator->errors(),
                'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

            throw new ValidationException($validator, $response);
        }

        $cost = $this->costRepository->findOrFail($id);

        try {
            $cost->update($validator->validate());

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $cost);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
