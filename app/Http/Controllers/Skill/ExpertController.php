<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Filters\Skill\Expert\Search;
use App\Http\Filters\Skill\Expert\Sort;
use App\Http\Requests\Skill\Expert\ExpertSkillRequestStore;
use App\Http\Resources\Skill\Expert\ExpertCollection;
use App\Http\Resources\Skill\Expert\ExpertSkillCollection;
use App\Http\Resources\Skill\Expert\ExpertSkillDetail;
use App\Repositories\ExpertSkill\ExpertSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExpertController extends Controller
{
    protected $expertSkillRepository;

    public function __construct(ExpertSkillRepository $expertSkillRepository)
    {
        $this->expertSkillRepository = $expertSkillRepository;
    }

    public function index(Request $request)
    {
        $expertSkills = app(Pipeline::class)
            ->send($this->expertSkillRepository->query())
            ->through([
                Search::class,
                Sort::class,
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new ExpertSkillCollection($expertSkills);
    }

    public function store(ExpertSkillRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
            $request->merge([
                'uuid' => Str::uuid()
            ]);

            return $this->expertSkillRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        return new ExpertSkillDetail($expertSkill);
    }

    public function update(ExpertSkillRequestStore $request, $id)
    {
        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $expertSkill) {
            return $this->expertSkillRepository->update($expertSkill->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        return DB::transaction(function () use ($expertSkill) {
            return $this->expertSkillRepository->delete($expertSkill->id);
        });
    }
}
