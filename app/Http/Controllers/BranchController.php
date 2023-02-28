<?php

namespace App\Http\Controllers;

use App\Http\Filters\Branch\Search;
use App\Http\Requests\Branch\BranchRequestStore;
use App\Http\Resources\Branch\BranchCollection;
use App\Http\Resources\Branch\BranchDetail;
use Illuminate\Http\Request;
use App\Repositories\Branch\BranchRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
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
        return DB::transaction(function () use ($request) {
            return $this->branchRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $branch = $this->branchRepository->findOrFail($id);

        return new BranchDetail($branch);
    }

    public function update(BranchRequestStore $request, $id)
    {
        $branch = $this->branchRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $branch) {
            return $this->branchRepository->update($branch->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $branch = $this->branchRepository->findOrFail($id);

        return DB::transaction(function () use ($branch) {
            return $this->branchRepository->delete($branch->id);
        });
    }
}
