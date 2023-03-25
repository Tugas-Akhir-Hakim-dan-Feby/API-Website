<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Filters\Skill\Expert\Search;
use App\Http\Filters\Skill\Expert\Sort;
use App\Http\Requests\Skill\Expert\ExpertSkillRequestStore;
use App\Http\Resources\Skill\Expert\ExpertCollection;
use App\Http\Resources\Skill\Expert\ExpertSkillCollection;
use App\Http\Resources\Skill\Expert\ExpertSkillDetail;
use App\Http\Traits\MessageFixer;
use App\Repositories\ExpertSkill\ExpertSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExpertController extends Controller
{
    use MessageFixer;

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
        DB::beginTransaction();

        try {
            $request->merge([
                'uuid' => Str::uuid()
            ]);

            $skill = $this->expertSkillRepository->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $skill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        return new ExpertSkillDetail($expertSkill);
    }

    public function update(ExpertSkillRequestStore $request, $id)
    {
        DB::beginTransaction();

        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        try {
            $expertSkill->update($request->all());
            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $expertSkill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $expertSkill = $this->expertSkillRepository->findOrFail($id);

        try {
            $expertSkill->delete();
            DB::commit();
            return $this->successMessage("data berhasil dihapus", $expertSkill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
