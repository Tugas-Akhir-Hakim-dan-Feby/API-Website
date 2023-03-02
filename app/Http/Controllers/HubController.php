<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Hub\HubRequestStore;
use App\Http\Resources\Hub\HubCollection;
use App\Http\Resources\Hub\HubDetail;
use App\Repositories\AdminHub\AdminHubRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;


class HubController extends Controller
{
    protected $adminhubRepository;

    public function __construct(AdminHubRepository $adminHubRepository)
    {
        $this->adminhubRepository = $adminHubRepository;
    }

    public function index(Request $request)
    {
        $adminhubs = $this->adminhubRepository->all();
    }

    public function store(HubRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->adminhubRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $adminhubs = $this->adminhubRepository->findOrFail($id);

        return new HubDetail($adminhubs);
    }

    public function update(HubRequestStore $request, $id)
    {
        $adminhubs = $this->adminhubRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $adminhubs) {
            return $this->adminhubRepository->update($adminhubs->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $adminhubs = $this->adminhubRepository->findOrFail($id);

        return DB::transaction(function () use ($adminhubs) {
            return $this->adminhubRepository->delete($adminhubs->id);
        });
    }
}
