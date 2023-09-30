<?php

namespace App\Http\Controllers;

use App\Http\Traits\MessageFixer;
use App\Models\BenefitMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CostBenefitController extends Controller
{
    use MessageFixer;

    protected $benefit;

    public function __construct(BenefitMember $benefit)
    {
        $this->benefit = $benefit;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'cost_id' => 'required|exists:costs,id',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->warningMessage($validator->errors());
        }

        try {
            $benefit = $this->benefit->create($request->all());

            DB::commit();
            return $this->successMessage("data berhasil ditambahkan", $benefit);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $benefit = $this->benefit->findOrFail($id);

        try {
            $benefit->delete();

            DB::commit();
            return $this->successMessage("data berhasil ditambahkan", $benefit);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
