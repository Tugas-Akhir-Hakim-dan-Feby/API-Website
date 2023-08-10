<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Operator\Role as OperatorRole;
use App\Http\Filters\User\Operator\Search;
use App\Http\Requests\User\Operator\OperatorRequestStore;
use App\Http\Resources\User\OperatorCollection;
use App\Http\Resources\User\OperatorDetail;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Models\Cost;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class OperatorController extends Controller
{
    use PaymentFixer, MessageFixer, UploadDocument;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $adminBranch = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                OperatorRole::class,
                Search::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new OperatorCollection($adminBranch);
    }

    public function store(OperatorRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);

        $role = Role::findById(User::OPERATOR, 'api');

        $request->merge([
            'uuid' => Str::uuid(),
        ]);

        try {
            $this->pay(Cost::TEST_INSTITUTION);

            $user->update([
                "role_id" => $role->id,
            ]);
            $user->operator()->create($request->all());

            $this->upload($request->file('document_logo'), $user->operator->logo(), 'tuk_logo');

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->payment);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        $user->load(['operator.logo', 'operator.examPackets.competenceSchema']);

        return new OperatorDetail($user);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->operator) {
                if ($user->operator?->logo) {
                    $path = str_replace(url('storage') . '/', '', $user->operator?->logo->document_path);
                    Storage::delete($path);
                }

                if ($user->operator?->examPackets) {
                    $user->operator?->examPackets()->delete();
                }

                $user->operator()->delete();
            }

            $user->removeRole(Role::findById(User::MEMBER_INDIVIDUAL, 'api'));
            $user->update(["role_id" => User::MEMBER_APPLICATION]);

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
