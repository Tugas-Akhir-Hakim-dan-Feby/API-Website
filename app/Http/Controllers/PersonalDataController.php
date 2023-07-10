<?php

namespace App\Http\Controllers;

use App\Http\Controllers\User\WelderMemberController;
use App\Http\Requests\PersonalData\PersonalDataRequestStore;
use App\Http\Resources\PersonalData\PersonalDataDetail;
use App\Repositories\User\UserRepository;
use App\Repositories\PersonalData\PersonalDataRepository;
use App\Repositories\WelderSkill\WelderSkillRepository;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Http\Traits\FillableFixer;
use App\Models\PersonalData;
use App\Models\User\WelderMember;
use App\Repositories\UserWelderMember\UserWelderMemberRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonalDataController extends Controller
{
    use MessageFixer, UploadDocument, FillableFixer;

    protected $personalDataRepository, $userRepository, $welderSkillRepository, $welderMemberRepository;

    public function __construct(
        PersonalDataRepository $personalDataRepository,
        UserRepository $userRepository,
        WelderSkillRepository $welderSkillRepository,
        UserWelderMemberRepository $welderMemberRepository
    ) {
        $this->personalDataRepository = $personalDataRepository;
        $this->userRepository = $userRepository;
        $this->welderSkillRepository = $welderSkillRepository;
        $this->welderMemberRepository = $welderMemberRepository;
    }

    public function store(PersonalDataRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid]);
        if (!$user) {
            abort(404);
        }

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

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->personalData);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show(string $id)
    {
        $user = $this->userRepository->findOrFail($id);
        $personalData = $this->personalDataRepository->findByCriteria(["user_id" => $user->id]);

        if (!$personalData) {
            return $this->customMessage("WARNING", "data personal tidak ditemukan", 404);
        }

        return new PersonalDataDetail($personalData);
    }
}
