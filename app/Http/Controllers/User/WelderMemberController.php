<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\WelderMember\Role;
use App\Http\Filters\User\WelderMember\WelderSkillId;
use App\Http\Requests\User\WelderMember\UploadFileRequest;
use App\Http\Requests\User\WelderMember\WelderRequestStore;
use App\Http\Resources\User\WelderMemberCollection;
use App\Http\Resources\User\WelderMemberDetail;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Imports\User\WelderMemberImport;
use App\Models\User;
use App\Models\User\WelderMember;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserWelderMember\UserWelderMemberRepository;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role as PermissionModelsRole;

class WelderMemberController extends Controller
{
    use MessageFixer, UploadDocument, PaymentFixer;

    protected $welderMemberRepository, $userRepository, $welderSkillRepository, $paymentRepository;

    public function __construct(
        UserWelderMemberRepository $welderMemberRepository,
        UserRepository $userRepository,
        WelderSkillRepository $welderSkillRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->welderMemberRepository = $welderMemberRepository;
        $this->userRepository = $userRepository;
        $this->welderSkillRepository = $welderSkillRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request)
    {
        $welderMembers = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                Role::class,
                WelderSkillId::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new WelderMemberCollection($welderMembers);
    }

    public function store(WelderRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid, 'role_id' => User::GUEST]);
        if (!$user) {
            abort(404);
        }

        $role = PermissionModelsRole::findById(User::MEMBER_WELDER, 'api');
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            'uuid' => Str::uuid(),
            'status' => WelderMember::INACTIVE,
            'welder_skill_id' => $welderSkill->id
        ]);


        try {
            if ($request->hasFile("document_certificate_school")) {
                $request->merge([
                    "certificate_school" => $this->storageFile($request->file("document_certificate_school"), "certificate_school")
                ]);
            }

            if ($request->hasFile("document_pas_photo")) {
                $request->merge([
                    "pas_photo" => $this->storageFile($request->file("document_pas_photo"), "pas_photo")
                ]);
            }

            if ($request->hasFile("document_certificate_competency")) {
                $this->upload($request->file("document_certificate_competency"), $user->welderDocuments(), "welder_document");
            }

            $this->pay();

            $user->update([
                "role_id" => $role->id
            ]);

            $user->welderMember()->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->payment);
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
            Excel::import(new WelderMemberImport, $request->file('file'));

            return $this->successMessage('data berhasil ditambahkan', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show(string $id)
    {
        $welderMember = $this->userRepository->findOrFail($id);
        if (!$welderMember) {
            abort(404);
        }

        $welderMember->load(["welderMember.welderSkill"]);

        return new WelderMemberDetail($welderMember);
    }

    public function edit(WelderMember $welderMember)
    {
        //
    }

    public function update(Request $request, WelderMember $welderMember)
    {
        //
    }

    public function destroy(WelderMember $welderMember)
    {
        //
    }
}
