<?php

namespace App\Http\Controllers;

use App\Http\Filters\RegisterJob\SearchJobVacancy;
use App\Http\Requests\RegisterJob\RegisterJobRequestStore;
use App\Http\Resources\RegisterJob\RegisterJobCollection;
use App\Http\Resources\RegisterJob\RegisterJobDetail;
use App\Http\Traits\MessageFixer;
use App\Mail\SendAcceptWork;
use App\Mail\SendRejectWork;
use App\Models\RegisterJob;
use App\Repositories\JobVacancy\JobVacancyRepository;
use App\Repositories\RegisterJob\RegisterJobRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegisterJobController extends Controller
{
    use MessageFixer;

    protected $jobVacancyRepository, $registerJobRepository, $userRepository;

    public function __construct(JobVacancyRepository $jobVacancyRepository, RegisterJobRepository $registerJobRepository,  UserRepository $userRepository)
    {
        $this->jobVacancyRepository = $jobVacancyRepository;
        $this->registerJobRepository = $registerJobRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $registerJobs = app(Pipeline::class)
            ->send($this->registerJobRepository->query())
            ->through([
                SearchJobVacancy::class
            ])
            ->thenReturn()
            ->with(["user.welderHasSkills.welderSkill", "user.personalData", "user.detailWorker", "user.welderDocuments"])
            ->paginate($request->per_page);

        return new RegisterJobCollection($registerJobs);
    }

    public function store(RegisterJobRequestStore $request)
    {
        DB::beginTransaction();

        $jobVacancy = $this->jobVacancyRepository->findOrFail($request->job_vacancy_id);

        $request->merge([
            "user_id" => auth()->user()->id
        ]);

        try {
            $jobVacancy->registerJobs()->create($request->all());
            DB::commit();
            return $this->successMessage("lamaran berhasil diregister.", $jobVacancy->registerJobs);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function check($id)
    {
        $jobVacancy = $this->jobVacancyRepository->findOrFail($id);

        $registerJob = $this->registerJobRepository->findByCriteria([
            "user_id" => auth()->user()->id,
            "job_vacancy_id" => $jobVacancy->id
        ]);

        if (!$registerJob) {
            return 0;
        }

        return 1;
    }

    public function show($id)
    {
        $user = $this->userRepository->findOrFail($id);
        $user->load(["welderHasSkills.welderSkill", "personalData", "detailWorker", "welderDocuments"]);

        return new RegisterJobDetail($user);
    }

    public function accept(Request $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($request->user_id);
        $jobVacancy = $this->jobVacancyRepository->findOrFail($request->job_vacancy_id);

        try {
            $user->registerJob()->update(["status" => RegisterJob::ACCEPT]);

            Mail::to($user->email)->send(new SendAcceptWork($user, $jobVacancy));

            DB::commit();
            return $this->successMessage("lamaran berhasil diterima.", $user->registerJob);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function reject(Request $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($request->user_id);
        $jobVacancy = $this->jobVacancyRepository->findOrFail($request->job_vacancy_id);

        try {
            $user->registerJob()->update(["status" => RegisterJob::REJECT]);

            Mail::to($user->email)->send(new SendRejectWork($user, $jobVacancy));

            DB::commit();
            return $this->successMessage("lamaran berhasil diterima.", $user->registerJob);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
