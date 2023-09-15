<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\WelderMember\Role;
use App\Http\Filters\User\WelderMember\Search;
use App\Http\Filters\User\WelderMember\WelderSkillId;
use App\Http\Requests\User\WelderMember\UpdateDocumentRequest;
use App\Http\Requests\User\WelderMember\UploadFileRequest;
use App\Http\Requests\User\WelderMember\WelderRequestStore;
use App\Http\Requests\User\WelderMember\WelderRequestUpdate;
use App\Http\Resources\User\WelderMemberCollection;
use App\Http\Resources\User\WelderMemberDetail;
use App\Http\Traits\FillableFixer;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Imports\User\WelderMemberImport;
use App\Models\Cost;
use App\Models\User;
use App\Models\User\WelderMember;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\PersonalData\PersonalDataRepository;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role as PermissionModelsRole;

class WelderMemberController extends Controller
{
    use MessageFixer, UploadDocument, PaymentFixer, FillableFixer;

    protected $welderMemberRepository, $userRepository, $welderSkillRepository, $paymentRepository, $personalDataRepository;

    public function __construct(
        UserWelderMemberRepository $welderMemberRepository = null,
        UserRepository $userRepository = null,
        WelderSkillRepository $welderSkillRepository = null,
        PaymentRepository $paymentRepository = null,
        PersonalDataRepository $personalDataRepository
    ) {
        $this->welderMemberRepository = $welderMemberRepository;
        $this->userRepository = $userRepository;
        $this->welderSkillRepository = $welderSkillRepository;
        $this->paymentRepository = $paymentRepository;
        $this->personalDataRepository = $personalDataRepository;
    }

    public function index(Request $request)
    {
        $welderMembers = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                Role::class,
                Search::class,
                WelderSkillId::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new WelderMemberCollection($welderMembers);
    }

    public function store(WelderRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid]);
        if (!$user) {
            abort(404);
        }

        $role = PermissionModelsRole::findById(User::MEMBER_INDIVIDUAL, 'api');

        $request->merge([
            'uuid' => Str::uuid(),
            'status' => WelderMember::INACTIVE
        ]);

        try {
            if ($request->hasFile("document_curriculum_vitae")) {
                $request->merge([
                    "curriculum_vitae" => $this->storageFile($request->file("document_curriculum_vitae"), "curriculum_vitae")
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

            foreach ($request->welder_skill_ids as $welderSkillId) {
                $welderSkill = $this->welderSkillRepository->findOrFail($welderSkillId);
                $user->welderHasSkills()->create([
                    "welder_skill_id" => $welderSkill->id
                ]);
            }

            $welderMember = $this->onlyFillables($request->all(), $this->welderMemberRepository->getFillable());
            $user->welderMember()->create($welderMember);

            $personalData = $this->onlyFillables($request->all(), $this->personalDataRepository->getFillable());
            $user->personalData()->create($personalData);

            $this->pay(Cost::WELDER_MEMBER);

            $user->update([
                "role_id" => $role->id,
                'membership_card' => "MC-" . date('Ymd') . $user->id
            ]);

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->payment);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function storeMember()
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid]);
        if (!$user) {
            abort(404);
        }

        $role = PermissionModelsRole::findById(User::MEMBER_INDIVIDUAL, 'api');

        try {
            $this->pay(Cost::WELDER_MEMBER);

            $user->update([
                "role_id" => $role->id,
                'membership_card' => "MC-" . date('Ymd') . $user->id
            ]);

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

        $welderMember->load(["welderHasSkills.welderSkill", "welderDocuments", "welderMember"]);

        return new WelderMemberDetail($welderMember);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(WelderRequestUpdate $request, string $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($id);
        if (!$user) {
            abort(404);
        }

        try {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user->update($fillableUser);

            $fillableWelderMember = $this->onlyFillables($request->all(), $this->welderMemberRepository->getFillable());
            $user->welderMember()->update($fillableWelderMember);

            $fillablePersonalData = $this->onlyFillables($request->all(), $this->personalDataRepository->getFillable());
            $user->personalData()->update($fillablePersonalData);

            $welderSkillIds = $request->welder_skill_ids;
            $welderSkillIds = $this->welderSkillRepository->whereIn($welderSkillIds);
            $welderSkillIds = $welderSkillIds->pluck('id')->toArray();

            $existingWelderSkills = $user->welderHasSkills()
                ->whereIn('welder_skill_id', $welderSkillIds)
                ->pluck('welder_skill_id')
                ->toArray();

            $newSkillIds = array_diff($welderSkillIds, $existingWelderSkills);

            if (!empty($newSkillIds)) {
                $skillsToCreate = [];
                foreach ($newSkillIds as $skillId) {
                    $skillsToCreate[] = [
                        'welder_skill_id' => $skillId,
                        'user_id' => auth()->user()->id
                    ];
                }

                $user->welderHasSkills()->insert($skillsToCreate);
            }

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateDocument(UpdateDocumentRequest $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($id);
        if (!$user) {
            abort(404);
        }

        try {
            if ($request->hasFile('document_pas_photo')) {
                if ($user->welderMember->pas_photo) {
                    $path = str_replace(url('storage') . '/', '', $user->welderMember->pas_photo);
                    Storage::delete($path);
                }

                $user->welderMember()->update([
                    "pas_photo" => $request->file('document_pas_photo')->store('pas_photo')
                ]);
            }

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
            if ($user->expert) {
                if ($user->expert?->certificate_competency) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->certificate_competency);
                    Storage::delete($path);
                }

                if ($user->expert?->certificate_profession) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->certificate_profession);
                    Storage::delete($path);
                }

                if ($user->expert?->working_mail) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->working_mail);
                    Storage::delete($path);
                }

                if ($user->expert?->career) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->career);
                    Storage::delete($path);
                }

                $user->expert()->delete();
            }

            if ($user->welderHasSkills) {
                $user->welderHasSkills()->delete();
            }

            if ($user->welderDocuments) {
                foreach ($user->welderDocuments as $welderDocument) {
                    $path = str_replace(url('storage') . '/', '', $welderDocument->document_path);
                    Storage::delete($path);
                    $welderDocument->delete();
                }
            }

            if ($user->welderMember) {
                if ($user->welderMember?->pas_photo) {
                    $path = str_replace(url('storage') . '/', '', $user->welderMember?->pas_photo);
                    Storage::delete($path);
                }

                $user->welderMember()->delete();
            }

            $user->removeRole(PermissionModelsRole::findById(User::EXPERT, 'api'));
            $user->removeRole(PermissionModelsRole::findById(User::MEMBER_INDIVIDUAL, 'api'));
            $user->update(["role_id" => User::MEMBER_APPLICATION]);

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function deleteSkill($id)
    {
        $skill = $this->welderSkillRepository->findOrFail($id);

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);
        if (!$user) {
            abort(404);
        }

        try {
            $user->welderHasSkills()->where([
                "welder_skill_id" => $skill->id
            ])->delete();

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
