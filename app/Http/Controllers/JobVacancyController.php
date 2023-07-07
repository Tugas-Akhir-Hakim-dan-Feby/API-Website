<?php

namespace App\Http\Controllers;

use App\Http\Filters\JobVacancy\LoggedIn;
use App\Http\Requests\JobVacancy\JobVacancyRequestStore;
use App\Http\Requests\JobVacancy\JobVacancyRequestUpdate;
use App\Http\Resources\JobVacancy\JobVacancyCollection;
use App\Http\Resources\JobVacancy\JobVacancyDetail;
use App\Http\Traits\MessageFixer;
use App\Models\JobVacancy;
use App\Repositories\JobVacancy\JobVacancyRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobVacancyController extends Controller
{
    use MessageFixer;

    protected $jobVacancyRepository, $userRepository, $welderSkillRepository;

    public function __construct(
        JobVacancyRepository $jobVacancyRepository,
        UserRepository $userRepository,
        WelderSkillRepository $welderSkillRepository
    ) {
        $this->jobVacancyRepository = $jobVacancyRepository;
        $this->userRepository = $userRepository;
        $this->welderSkillRepository = $welderSkillRepository;
    }

    public function index(Request $request)
    {
        $jobVacancies = app(Pipeline::class)
            ->send($this->jobVacancyRepository->query())
            ->through([
                LoggedIn::class
            ])
            ->thenReturn()
            ->with(["welderSkill", "companyMember"])
            ->paginate($request->per_page);

        return new JobVacancyCollection($jobVacancies);
    }

    public function store(JobVacancyRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail(auth()->user()->uuid);
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            "welder_skill_id" => $welderSkill->id,
            "status"  => JobVacancy::ACTIVE,
            "slug"  => Str::slug($welderSkill->skill_name) . "-" . mt_rand(000000, 999999) . $user->companyMember->id,
            "uuid" => Str::uuid(),
        ]);

        try {
            if ($request->hasFile('document_pamphlet')) {
                $request->merge([
                    "pamphlet" => $request->file('document_pamphlet')->store('document_pamphlet')
                ]);
            }

            $user->companyMember->jobVacancies()->create($request->all());

            DB::commit();
            return $this->successMessage("data berhasil ditambahkan.", $user->companyMember->jobVacancies);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show(string $id)
    {
        $jobVacancy = $this->jobVacancyRepository->findOrFail($id);
        $jobVacancy->load(["welderSkill", "companyMember"]);

        return new JobVacancyDetail($jobVacancy);
    }

    public function showBySlug(string $slug)
    {
        $jobVacancy = $this->jobVacancyRepository->findByCriteria(["slug" => $slug]);
        $jobVacancy->load(["welderSkill", "companyMember"]);

        return new JobVacancyDetail($jobVacancy);
    }

    public function update(JobVacancyRequestUpdate $request, string $id)
    {
        DB::beginTransaction();

        $jobVacancy = $this->jobVacancyRepository->findOrFail($id);
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            "welder_skill_id" => $welderSkill->id,
        ]);

        try {
            if ($request->hasFile('document_pamphlet')) {
                if ($jobVacancy->pamphlet) {
                    $path = str_replace(url('storage') . '/', '', $jobVacancy->pamphlet);
                    Storage::delete($path);
                }

                $request->merge([
                    "pamphlet" => $request->file('document_pamphlet')->store('document_pamphlet')
                ]);
            }

            $jobVacancy->update($request->all());

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui.", $jobVacancy);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        $jobVacancy = $this->jobVacancyRepository->findOrFail($id);

        try {
            if ($jobVacancy->pamphlet) {
                $path = str_replace(url('storage') . '/', '', $jobVacancy->pamphlet);
                Storage::delete($path);
            }

            $jobVacancy->delete();

            DB::commit();
            return $this->successMessage("data berhasil dihapus.", []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
