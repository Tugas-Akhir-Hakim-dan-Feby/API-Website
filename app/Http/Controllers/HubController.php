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
    protected $adminHubRepository;

    public function __construct(AdminHubRepository $adminHubRepository)
    {
        $this->adminHubRepository = $adminHubRepository;
    }

    public function index(Request $request)
    {
        $adminhubs = $this->adminHubRepository->all();
        return new HubCollection($adminhubs);
    }

    public function store(HubRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            return $this->adminHubRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $adminhubs = $this->adminHubRepository->findOrFail($id);
        return new HubDetail($adminhubs);
    }

    public function update(HubRequestStore $request, $id)
    {
        $adminhubs = $this->adminHubRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $adminhubs) {
            return $this->adminHubRepository->update($adminhubs->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $adminhubs = $this->adminHubRepository->findOrFail($id);

        return DB::transaction(function () use ($adminhubs) {
            return $this->adminHubRepository->delete($adminhubs->id);
        });
    }
}
