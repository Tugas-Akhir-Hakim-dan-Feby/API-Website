<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Hub\HubDetail;
use App\Http\Resources\Hub\HubCollection;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Hub\HubRequestStore;
use App\Repositories\AdminHub\AdminHubRepository;
use App\Models\Role;


class HubController extends Controller
{
    protected $userRepository, $adminHubRepository;

    public function __construct(UserRepository $userRepository, AdminHubRepository $adminHubRepository)
    {
        $this->userRepository = $userRepository;
        $this->adminHubRepository = $adminHubRepository;
    }

    public function index(Request $request)
    {
        $adminhubs = $this->userRepository->findByCriteria(['role_id' =>Role::ADMIN_PUSAT]);
        return new HubCollection($adminhubs);
    }

    public function store(HubRequestStore $request)
    {

        return DB::transaction(function () use ($request) {
            $request->merge([
                'password' => bcrypt(Str::random(10))
            ]);
            $user = $this->userRepository->create($request->all());
            $request->merge([
                'user_id' => $user->id
            ]);
            return $this->adminHubRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $adminhubs = $this->userRepository->findOrFail($id);
        return new HubDetail($adminhubs);
    }

    public function update(HubRequestStore $request, $id)
    {
        $adminhubs = $this->userRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $adminhubs) {
            $this->userRepository->update($adminhubs->id, $request->all());
            return $this->adminHubRepository->update($adminhubs->adminHub->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findOrFail($id);

        return DB::transaction(function () use ($user) {
            if ($user->adminHub){
                $user->adminHub->delete();
            }

            return $user->delete();
        });
    }
}
