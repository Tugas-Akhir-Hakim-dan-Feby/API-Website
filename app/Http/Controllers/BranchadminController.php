<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Branch\BranchadminDetail;
use App\Http\Resources\Branch\BranchadminCollection;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Branch\BranchadminRequestStore;
use App\Repositories\BranchAdmin\BranchAdminRepository;
use App\Models\Role;


class BranchadminController extends Controller
{
    protected $userRepository, $branchAdminRepository;

    public function __construct(UserRepository $userRepository, BranchAdminRepository $branchAdminRepository)
    {
        $this->userRepository = $userRepository;
        $this->branchAdminRepository = $branchAdminRepository;
    }

    public function index(Request $request)
{
    $branchAdmins = $this->userRepository->findByCriteria(['role_id' =>Role::ADMIN_BRANCH]);
    return new BranchadminCollection($branchAdmins);
}


    public function store(BranchadminRequestStore $request)
    {

        return DB::transaction(function () use ($request) {
            $request->merge([
                'password' => bcrypt(Str::random(10)),
                'role_id' => Role::ADMIN_BRANCH
            ]);
            $user = $this->userRepository->create($request->all());
            $request->merge([
                'user_id' => $user->id,
                'branch_id' => $request->branch_id,
            ]);
            return $this->branchAdminRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $branchAdmins = $this->userRepository->findOrFail($id);
        return new BranchadminDetail($branchAdmins);
    }

    public function update(BranchadminRequestStore $request, $id)
    {
        $branchAdmins = $this->userRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $branchAdmins) {
            $this->userRepository->update($branchAdmins->id, $request->all());
            return $this->branchAdminRepository->update($branchAdmins->branchAdmin->id, $request->all());
        });
    }

    public function destroy($id)
{
        $user = $this->userRepository->findOrFail($id);


        return DB::transaction(function () use ($id, $user){
        if ($user->branchAdmin){
            $user->branchAdmin->delete();
        }

        return $user->delete();
    });
}
}
