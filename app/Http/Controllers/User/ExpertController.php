<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\Expert\Approved;
use App\Http\Filters\User\Expert\Role as ExpertRole;
use App\Http\Filters\User\Expert\Search;
use App\Http\Requests\User\Expert\ExpertRequestStore;
use App\Http\Requests\User\Expert\ExpertRequestUpdate;
use App\Http\Requests\User\Expert\UploadFileRequest;
use App\Http\Resources\User\Expert\ExpertCollection;
use App\Http\Resources\User\Expert\ExpertDetail;
use App\Http\Traits\FillableFixer;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Imports\User\ExpertImport;
use App\Models\PersonalData;
use App\Models\User;
use App\Models\User\Expert;
use App\Models\User\WelderMember;
use App\Models\WelderSkill;
use App\Repositories\User\UserRepository;
use App\Repositories\UserExpert\UserExpertRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class ExpertController extends Controller
{
    use MessageFixer, UploadDocument, FillableFixer;

    protected $userRepository, $userExpertRepository, $welderMember, $personalData, $welderSkill;

    public function __construct(
        UserRepository $userRepository,
        UserExpertRepository $userExpertRepository,
        WelderMember $welderMember,
        PersonalData $personalData,
        WelderSkill $welderSkill
    ) {
        $this->userRepository = $userRepository;
        $this->userExpertRepository = $userExpertRepository;
        $this->welderMember = $welderMember;
        $this->personalData = $personalData;
        $this->welderSkill = $welderSkill;
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

        $role = Role::findById(User::EXPERT, 'api');

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

        $user->load(['expert', 'welderHasSkills.welderSkill', 'welderDocuments', 'welderMember']);

        return new ExpertDetail($user);
    }

    public function update(ExpertRequestUpdate $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);
        if (!$user) {
            abort(404);
        }

        try {
            $fillableUser = $this->onlyFillables($request->all(), $this->userRepository->getFillable());
            $user->update($fillableUser);

            $fillableWelderMember = $this->onlyFillables($request->all(), $this->welderMember->getFillable());
            if ($user->welderMember) {
                $user->welderMember()->update($fillableWelderMember);
            } else {
                $user->welderMember()->create($fillableWelderMember);
            }

            $fillablePersonalData = $this->onlyFillables($request->all(), $this->personalData->getFillable());
            if ($user->personalData) {
                $user->personalData()->update($fillablePersonalData);
            } else {
                $user->personalData()->create($fillablePersonalData);
            }

            if ($user->expert) {
                $user->expert()->update([
                    "instance" => $request->instance
                ]);
            } else {
                $user->expert()->update([
                    "instance" => $request->instance
                ]);
            }

            if ($request->welder_skill_ids) {
                $welderSkillIds = $request->welder_skill_ids;
                $welderSkillIds = $this->welderSkill->whereIn('uuid', $welderSkillIds)->get();
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
            }

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
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
            $user->syncRoles($role);

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

            $user->syncRoles(Role::findById(User::MEMBER_INDIVIDUAL, 'api'));
            $user->update(["role_id" => User::MEMBER_INDIVIDUAL]);

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateCertificateProfession(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'document_certificate_profession' => 'required|file|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'WARNING',
                'messages' => $validator->errors(),
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->expert) {
                if ($user->expert?->certificate_profession) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->certificate_profession);
                    Storage::delete($path);
                }
            }

            $user->expert()->update([
                "certificate_profession" => $request->file('document_certificate_profession')->store('certificate_profession')
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateWorkingMail(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'document_working_mail' => 'required|file|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'WARNING',
                'messages' => $validator->errors(),
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->expert) {
                if ($user->expert?->working_mail) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->working_mail);
                    Storage::delete($path);
                }
            }

            $user->expert()->update([
                "working_mail" => $request->file('document_working_mail')->store('working_mail')
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateCareer(Request $request, $id)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'document_career' => 'required|file|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'WARNING',
                'messages' => $validator->errors(),
                'status_code' => Response::HTTP_UNPROCESSABLE_ENTITY
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->expert) {
                if ($user->expert?->career) {
                    $path = str_replace(url('storage') . '/', '', $user->expert?->career);
                    Storage::delete($path);
                }
            }

            $user->expert()->update([
                "career" => $request->file('document_career')->store('career')
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
