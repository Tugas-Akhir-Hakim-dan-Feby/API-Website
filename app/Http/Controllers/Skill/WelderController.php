<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Filters\Skill\Welder\Search;
use App\Http\Filters\Skill\Welder\Sort;
use App\Http\Requests\Skill\Welder\WelderSkillRequestStore;
use App\Http\Resources\Skill\Welder\WelderSkillCollection;
use App\Http\Resources\Skill\Welder\WelderSkillDetail;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WelderController extends Controller
{
    protected $welderSkillRepository;

    public function __construct(WelderSkillRepository $welderSkillRepository)
    {
        $this->welderSkillRepository = $welderSkillRepository;
    }

    public function index(Request $request)
    {
        $welderSkills = app(Pipeline::class)
            ->send($this->welderSkillRepository->query())
            ->through([
                Search::class,
                Sort::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new WelderSkillCollection($welderSkills);
    }

    public function store(WelderSkillRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            $request->merge([
                'uuid' => Str::uuid()
            ]);

            return $this->welderSkillRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        return new WelderSkillDetail($welderSkill);
    }

    public function update(WelderSkillRequestStore $request, $id)
    {
        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        return DB::transaction(function () use ($welderSkill, $request) {
            return $this->welderSkillRepository->update($welderSkill->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        return DB::transaction(function () use ($welderSkill) {
            return $this->welderSkillRepository->delete($welderSkill->id);
        });
    }
}
