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
    use FillableFixer;

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
        return DB::transaction(function () use ($request) {
            $role = PermissionModelsRole::find(ModelsRole::ADMIN_PUSAT);

            // Proses untuk create data user authentication
            $request->merge([
                'uuid' => Str::uuid(),
                'password' => bcrypt(Str::random(10)),
                'role_id' => ModelsRole::ADMIN_PUSAT
            ]);
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user = $this->userRepository->create($fillableUser);
            $user->syncRoles($role);

            // Proses untuk create data admin pusat
            $request->merge([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'status' => Hub::ACTIVE
            ]);
            $fillableAdminHub = $this->onlyFillables($request->all(), $this->adminHubRepository->getFillable());
            return $this->adminHubRepository->create($fillableAdminHub);
        });
    }

    public function show($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        $user->load('adminHub');

        return new HubDetail($user);
    }

    public function update(AdminHubRequestUpdate $request, $id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($request, $user) {
            // Proses untuk update data user
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $this->userRepository->update($user->id, $fillableUser);

            // Proses untuk update data admin pusat
            $request->merge([
                'status' => Hub::ACTIVE
            ]);
            $fillableAdminHub = $this->onlyFillables($request->all(), $this->adminHubRepository->getFillable());
            return $this->adminHubRepository->update($user->adminHub->id, $fillableAdminHub);
        });
    }

    public function updateStatus($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($user) {
            return $this->adminHubRepository->update($user->adminHub->id, [
                'status' => $user->adminHub->status ? Hub::INACTIVE : Hub::ACTIVE
            ]);
        });
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id, 'role_id' => ModelsRole::ADMIN_PUSAT]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($user) {
            // Proses untuk delete data admin pusat
            if ($user->adminHub) {
                $user->adminHub->delete();
            }

            // Proses untuk delete data user
            return $user->delete();
        });
    }
}
