<?php

namespace App\Http\Controllers;

use App\Http\Resources\Chart\SkillChart;
use App\Models\User\WelderMember;
use App\Models\WelderSkill;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{

    public function skillChart(Request $request)
    {
        $data = [];

        $welderSkills = WelderSkill::all();

        $startDate = Carbon::now()->subYears(5)->startOfYear();
        $endDate = Carbon::now()->endOfYear();

        foreach ($welderSkills as $welderSkill) {
            $welderMember = $welderSkill->welderMember()->whereYear('created_at', '>=', $startDate->year)
                ->whereYear('created_at', '<=', $endDate->year)
                ->get();

            $data["info"] = $endDate->year - $startDate->year . " tahun terakhir";
            $data["data"][$welderSkill->skill_name] = $welderMember->count();
        }

        return $data;
    }
}
