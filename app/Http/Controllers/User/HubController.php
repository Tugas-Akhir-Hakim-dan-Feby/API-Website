<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Hub\Role;
use App\Http\Filters\User\Hub\Search;
use App\Http\Filters\User\Hub\Sort;
use App\Http\Requests\User\Hub\AdminHubRequestStore;
use App\Http\Requests\User\Hub\AdminHubRequestUpdate;
use App\Http\Resources\User\HubCollection;
use App\Http\Resources\User\HubDetail;
use App\Http\Traits\FillableFixer;
use App\Http\Traits\MessageFixer;
use App\Models\Role as ModelsRole;
use App\Models\User\Hub;
use App\Repositories\User\UserRepository;
use App\Repositories\UserHub\UserHubRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as PermissionModelsRole;

class HubController extends Controller
{
    use FillableFixer, MessageFixer;

    protected $userRepository, $adminHubRepository;

    public function __construct(UserRepository $userRepository, UserHubRepository $adminHubRepository)
    {
        $this->userRepository = $userRepository;
        $this->adminHubRepository = $adminHubRepository;
    }

    public function index(Request $request)
    {
        $adminHubs = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                Role::class,
                Search::class,
                Sort::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new HubCollection($adminHubs);
    }

    public function store(AdminHubRequestStore $request)
    {
        DB::beginTransaction();

        $role = PermissionModelsRole::find(ModelsRole::ADMIN_PUSAT);

        try {
            $request->merge([
                'uuid' => Str::uuid(),
                'password' => bcrypt('password'),
                'role_id' => ModelsRole::ADMIN_PUSAT,
                'remember_token' => Str::random(20),
                'email_verified_at' => now()
            ]);
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user = $this->userRepository->create($fillableUser);
            $user->syncRoles($role);

            $request->merge([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'status' => Hub::ACTIVE
            ]);
            $fillableAdminHub = $this->onlyFillables($request->all(), $this->adminHubRepository->getFillable());

            $user->adminHub()->create($fillableAdminHub);
            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        $user->load(['adminHub', 'document']);

        return new HubDetail($user);
    }

    public function update(AdminHubRequestUpdate $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        try {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user->update($fillableUser);

            $fillableAdminHub = $this->onlyFillables($request->all(), $this->adminHubRepository->getFillable());
            $user->adminHub()->update($fillableAdminHub);
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

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        try {
            $user->adminHub()->update([
                'status' => $user->adminHub->status ? Hub::INACTIVE : Hub::ACTIVE
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

        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->adminHub) {
                $user->adminHub()->delete();
            }

            if ($user->document) {
                $user->document()->delete();
            }

            if ($user->articles) {
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
