<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Branch\Role;
use App\Http\Filters\User\Branch\Search;
use App\Http\Filters\User\Branch\Sort;
use App\Http\Requests\User\Branch\AdminBranchRequestStore;
use App\Http\Requests\User\Branch\AdminBranchRequestUpdate;
use App\Http\Resources\User\BranchCollection;
use App\Http\Resources\User\BranchDetail;
use App\Http\Traits\FillableFixer;
use App\Http\Traits\MessageFixer;
use App\Models\User;
use App\Models\User\Branch;
use App\Repositories\Branch\BranchRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserBranch\UserBranchRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                Search::class,
                Sort::class
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
            $role = ModelsRole::find(User::ADMIN_BRANCH);

            $request->merge([
                'uuid' => Str::uuid(),
                'password' => bcrypt('password'),
                'role_id' => User::ADMIN_BRANCH,
                'remember_token' => Str::random(20),
                'email_verified_at' => now(),
                'nip' => 1
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
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => User::ADMIN_BRANCH]);

        if (!$user) {
            abort(404);
        }

        $user->load(['adminBranch.branch', 'document']);

        return new BranchDetail($user);
    }

    public function update(AdminBranchRequestUpdate $request, $id)
    {
        DB::beginTransaction();

        $branch = $this->branchRepository->findOrFail($request->branch_id);
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => User::ADMIN_BRANCH]);

        if (!$user) {
            abort(404);
        }

        try {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user->update($fillableUser);

            $request->merge([
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

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => User::ADMIN_BRANCH]);

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

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => User::ADMIN_BRANCH]);

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

            if ($user->articles) {
                foreach ($user->articles as $article) {
                    if ($article->document) {
                        $path = str_replace(url('storage') . '/', '', $article->document->document_path);
                        Storage::delete($path);

                        $article->document()->delete();
                    }
                }

                $user->articles()->delete();
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
