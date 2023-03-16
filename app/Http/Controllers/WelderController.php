<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Filters\Skill\Expert\Search;
use App\Http\Filters\Skill\Expert\Sort;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Welder\WelderDetail;
use App\Repositories\Welder\WelderRepository;
use App\Http\Resources\Welder\WelderCollection;
use App\Http\Requests\Welder\WelderRequestStore;
use Illuminate\Pipeline\Pipeline;

class WelderController extends Controller
{
    protected $welderRepository;

    public function __construct(WelderRepository $welderRepository)
    {
        $this->welderRepository = $welderRepository;
    }

    public function index(Request $request)
    {
        $welders= app(Pipeline::class)
            ->send($this->welderRepository->query())
            ->through([
                Search::class,
                Sort::class,
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new WelderCollection($welders);
    }

    public function store(WelderRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->welderRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $welders = $this->welderRepository->findOrFail($id);

        return new WelderDetail($welders);
    }

    public function update(WelderRequestStore $request, $id)
    {
        $welders = $this->welderRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $welders) {
            return $this->welderRepository->update($welders->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $welders = $this->welderRepository->findOrFail($id);

        return DB::transaction(function () use ($welders) {
            return $this->welderRepository->delete($welders->id);
        });
    }
}
