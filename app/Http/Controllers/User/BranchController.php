<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Branch\Role;
use App\Http\Requests\User\Branch\AdminBranchRequestStore;
use App\Http\Requests\User\Branch\AdminBranchRequestUpdate;
use App\Http\Resources\User\BranchCollection;
use App\Http\Resources\User\BranchDetail;
use App\Http\Traits\FillableFixer;
use App\Models\Role as AppModelsRole;
use App\Models\User\Branch;
use App\Repositories\Branch\BranchRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserBranch\UserBranchRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as ModelsRole;

class BranchController extends Controller
{
    use FillableFixer;

    protected $userRepository, $adminBranchRepository, $branchRepository;

    public function __construct(UserRepository $userRepository, UserBranchRepository $adminBranchRepository, BranchRepository $branchRepository)
    {
        $this->userRepository = $userRepository;
        $this->branchRepository = $branchRepository;
        $this->adminBranchRepository = $adminBranchRepository;
    }

    public function index(Request $request)
    {
        $adminBranch = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                Role::class,
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new BranchCollection($adminBranch);
    }

    public function store(AdminBranchRequestStore $request)
    {
        $branch = $this->branchRepository->findOrFail($request->branch_id);

        return DB::transaction(function () use ($request, $branch) {
            $role = ModelsRole::find(AppModelsRole::ADMIN_CABANG);

            $request->merge([
                'uuid' => Str::uuid(),
                'password' => bcrypt(Str::random(10)),
                'role_id' => AppModelsRole::ADMIN_CABANG
            ]);

            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user = $this->userRepository->create($fillableUser);
            $user->syncRoles($role);

            $request->merge([
                'branch_id' => $branch->id,
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'status' => Branch::ACTIVE
            ]);
            $fillableAdminBranch = $this->onlyFillables($request->all(), $this->adminBranchRepository->getFillable());
            return $this->adminBranchRepository->create($fillableAdminBranch);
        });
    }

    public function show($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        $user->load(['adminBranch', 'document']);

        return new BranchDetail($user);
    }

    public function update(AdminBranchRequestUpdate $request, $id)
    {
        $branch = $this->branchRepository->findOrFail($request->branch_id);
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($request, $user, $branch) {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $this->userRepository->update($user->id, $fillableUser);

            $request->merge([
                'status' => $user->adminBranch->status ? Branch::INACTIVE : Branch::ACTIVE,
                'branch_id' => $branch->id,
            ]);
            $fillableAdminBranch = $this->onlyFillables($request->all(), $this->adminBranchRepository->getFillable());
            return $this->adminBranchRepository->update($user->adminBranch->id, $fillableAdminBranch);
        });
    }

    public function updateStatus($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($user) {
            return $this->adminBranchRepository->update($user->adminBranch->id, [
                'status' => $user->adminBranch->status ? Branch::INACTIVE : Branch::ACTIVE
            ]);
        });
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($user) {
            // Proses untuk delete data admin pusat
            if ($user->adminBranch) {
                $user->adminBranch->delete();
            }

            if ($user->document) {
                $user->document()->delete();
            }

            // Proses untuk delete data user
            return $user->delete();
        });
    }
}
