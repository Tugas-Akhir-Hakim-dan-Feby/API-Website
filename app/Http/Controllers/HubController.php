<?php

namespace App\Http\Controllers;

use App\Http\Filters\User\Hub\Role as HubRole;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Hub\HubDetail;
use App\Http\Resources\Hub\HubCollection;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Hub\HubRequestStore;
use App\Http\Traits\FillableFixer;
use App\Models\AdminHub;
use App\Repositories\AdminHub\AdminHubRepository;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class HubController extends Controller
{
    use FillableFixer;

    protected $userRepository, $adminHubRepository;

    public function __construct(UserRepository $userRepository, AdminHubRepository $adminHubRepository)
    {
        $this->userRepository = $userRepository;
        $this->adminHubRepository = $adminHubRepository;
    }

    public function index(Request $request)
    {
        $adminhubs = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                HubRole::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new HubCollection($adminhubs);
    }

    public function store(HubRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            $role = Permission::find(Role::ADMIN_APP);

            $request->merge([
                'uuid' => Str::uuid(),
                'password' => bcrypt(Str::random(10)),
                'role_id' => Role::ADMIN_APP
            ]);
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user = $this->userRepository->create($fillableUser);
            $user->syncRoles($role);

            $request->merge([
                'uuid' => Str::uuid(),
                'user_id' => $user->id,
                'status' => AdminHub::ACTIVE
            ]);
            $fillableAdminHub = $this->onlyFillables($request->all(), $this->adminHubRepository->getFillable());

            $user->adminHub()->create($fillableAdminHub);
            DB::commit();
            return $user;
        });
    }

    public function show($id)
    {
        $adminhubs = $this->userRepository->findOrFail($id);
        $adminhubs->load(['adminHub']);
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
            if ($user->adminHub) {
                $user->adminHub->delete();
            }

            return $user->delete();
        });
    }
}
