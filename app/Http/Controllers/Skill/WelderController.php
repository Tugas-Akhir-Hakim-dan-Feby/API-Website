<?php

namespace App\Http\Controllers\Skill;

use App\Http\Controllers\Controller;
use App\Http\Filters\Skill\Welder\Search;
use App\Http\Filters\Skill\Welder\Sort;
use App\Http\Requests\Skill\Welder\WelderSkillRequestStore;
use App\Http\Resources\Skill\Welder\WelderSkillCollection;
use App\Http\Resources\Skill\Welder\WelderSkillDetail;
use App\Http\Traits\MessageFixer;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WelderController extends Controller
{
    use MessageFixer;

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
        DB::beginTransaction();

        try {
            $request->merge([
                'uuid' => Str::uuid()
            ]);

            $skill = $this->welderSkillRepository->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $skill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        return new WelderSkillDetail($welderSkill);
    }

    public function update(WelderSkillRequestStore $request, $id)
    {
        DB::beginTransaction();

        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        try {
            $welderSkill->update($request->all());

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $welderSkill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $welderSkill = $this->welderSkillRepository->findOrFail($id);

        try {
            $welderSkill->delete();
            DB::commit();
            return $this->successMessage("data berhasil dihapus", $welderSkill);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
