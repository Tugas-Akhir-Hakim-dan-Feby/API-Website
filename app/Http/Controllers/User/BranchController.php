<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Branch\Role;
use App\Http\Requests\User\Branch\AdminBranchRequestStore;
use App\Http\Requests\User\Branch\AdminBranchRequestUpdate;
use App\Http\Resources\User\BranchCollection;
use App\Http\Resources\User\BranchDetail;
use App\Http\Traits\FillableFixer;
use App\Http\Traits\MessageFixer;
use App\Models\Role as AppModelsRole;
use App\Models\User\Branch;
use App\Repositories\Branch\BranchRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserBranch\UserBranchRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Spatie\Permission\Models\Role as ModelsRole;

class BranchController extends Controller
{
    use FillableFixer, MessageFixer;

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
        DB::beginTransaction();

        $branch = $this->branchRepository->findOrFail($request->branch_id);

        try {
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

            $user->adminBranch()->create($fillableAdminBranch);

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
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
        DB::beginTransaction();

        $branch = $this->branchRepository->findOrFail($request->branch_id);
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        try {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user->update($fillableUser);

            $request->merge([
                'status' => $user->adminBranch->status ? Branch::INACTIVE : Branch::ACTIVE,
                'branch_id' => $branch->id,
            ]);
            $fillableAdminBranch = $this->onlyFillables($request->all(), $this->adminBranchRepository->getFillable());

            $user->adminBranch()->update($fillableAdminBranch);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        try {
            $user->adminBranch()->update([
                'status' => $user->adminBranch->status ? Branch::INACTIVE : Branch::ACTIVE
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => AppModelsRole::ADMIN_CABANG]);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->adminBranch) {
                $user->adminBranch->delete();
            }

            if ($user->document) {
                $user->document()->delete();
            }

            $user->delete();

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
