<?php

namespace App\Http\Controllers;

use App\Http\Filters\Branch\Search;
use App\Http\Requests\Branch\BranchRequestStore;
use App\Http\Resources\Branch\BranchCollection;
use App\Http\Resources\Branch\BranchDetail;
use App\Http\Traits\MessageFixer;
use Illuminate\Http\Request;
use App\Repositories\Branch\BranchRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    use MessageFixer;

    protected $branchRepository;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function index(Request $request)
    {
        $branches = app(Pipeline::class)
            ->send($this->branchRepository->query())
            ->through([
                Search::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new BranchCollection($branches);
    }

    public function store(BranchRequestStore $request)
    {
        DB::beginTransaction();

        try {
            $request->merge([
                'uuid' => Str::uuid(),
            ]);

            $branch = $this->branchRepository->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $branch);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $branch = $this->branchRepository->findOrFail($id);

        return new BranchDetail($branch);
    }

    public function update(BranchRequestStore $request, $id)
    {
        DB::beginTransaction();

        $branch = $this->branchRepository->findOrFail($id);

        try {
            $branch->update($request->all());
            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $branch);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $branch = $this->branchRepository->findOrFail($id);

        try {
            $branch->delete();
            DB::commit();
            return $this->successMessage("data berhasil dihapus", $branch);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
