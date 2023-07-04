<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Expert\Approved;
use App\Http\Filters\User\Expert\Role as ExpertRole;
use App\Http\Filters\User\Expert\Search;
use App\Http\Requests\User\Expert\ExpertRequestStore;
use App\Http\Requests\User\Expert\UploadFileRequest;
use App\Http\Resources\User\Expert\ExpertCollection;
use App\Http\Resources\User\Expert\ExpertDetail;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Imports\User\ExpertImport;
use App\Models\User;
use App\Models\User\Expert;
use App\Repositories\User\UserRepository;
use App\Repositories\UserExpert\UserExpertRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class ExpertController extends Controller
{
    use MessageFixer, UploadDocument;

    protected $userRepository, $userExpertRepository;

    public function __construct(UserRepository $userRepository, UserExpertRepository $userExpertRepository)
    {
        $this->userRepository = $userRepository;
        $this->userExpertRepository = $userExpertRepository;
    }

    public function index(Request $request)
    {
        $userExperts = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                ExpertRole::class,
                Search::class,
                Approved::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new ExpertCollection($userExperts);
    }

    public function all(Request $request)
    {
        $userExperts = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                ExpertRole::class,
                Search::class,
                Approved::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new ExpertCollection($userExperts);
    }

    public function store(ExpertRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid]);
        if (!$user) {
            abort(404);
        }

        if ($user->expert) {
            return $this->warningMessage("data sudah terdaftar, harap tunggu konfirmasi dari admin.");
        }

        $role = Role::findById(User::PAKAR, 'api');

        $request->merge([
            'uuid' => Str::uuid(),
        ]);

        try {
            if ($request->hasFile("document_certificate_profession")) {
                $request->merge([
                    "certificate_profession" => $this->storageFile($request->file('document_certificate_profession'), "certificate_profession")
                ]);
            }

            if ($request->hasFile("document_certificate_competency")) {
                $request->merge([
                    "certificate_competency" => $this->storageFile($request->file('document_certificate_competency'), "certificate_competency")
                ]);
            }

            if ($request->hasFile("document_working_mail")) {
                $request->merge([
                    "working_mail" => $this->storageFile($request->file('document_working_mail'), "working_mail")
                ]);
            }

            if ($request->hasFile("document_career")) {
                $request->merge([
                    "career" => $this->storageFile($request->file('document_career'), "career")
                ]);
            }

            $user->update([
                "role_id" => $role->id
            ]);

            $user->expert()->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function uploadExcel(UploadFileRequest $request)
    {
        DB::beginTransaction();

        try {
            DB::commit();
            Excel::import(new ExpertImport, $request->file('file'));

            return $this->successMessage('data berhasil ditambahkan', []);
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

        $user->load(['expert', 'welderMember.welderSkill', 'welderDocuments']);

        return new ExpertDetail($user);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function confirmation($id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        $role = Role::findById($user->role_id, 'api');

        try {
            $user->expert()->update([
                'status' => $user->expert->status == Expert::NOT_APPROVED ? Expert::APPROVED : Expert::NOT_APPROVED
            ]);
            $user->assignRole($role);

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

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        try {
            $user->welderMember()->update([
                'status' => $user->welderMember->status ? Expert::INACTIVE : Expert::ACTIVE
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

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        try {
            $user->expert()->delete();

            $user->update(["role_id" => User::MEMBER_WELDER]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
