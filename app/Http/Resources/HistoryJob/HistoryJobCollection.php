<?php

namespace App\Http\Resources\HistoryJob;

use App\Models\RegisterJob;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryJobCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this as $history) {
            $data[] = [
                "company_name" => $history->jobVacancy->companyMember->company_name,
                "job_title" => $history->jobVacancy->welderSkill->skill_name,
                "work_type" => $history->jobVacancy->work_type,
                "placement" => $history->jobVacancy->placement,
                "salary" => $history->jobVacancy->salary,
                "status" => $this->getStatus($history->status),
            ];
        }

        return $data;
    }

    protected function getStatus($status)
    {
        $data = [
            "code" => $status,
            "message" => "Terdaftar"
        ];

        if ($status == RegisterJob::ACCEPT) {
            $data = [
                "code" => RegisterJob::ACCEPT,
                "message" => "Diterima"
            ];
        }

        if ($status == RegisterJob::REJECT) {
            $data = [
                "code" => RegisterJob::REJECT,
                "message" => "Ditolak"
            ];
        }

        return $data;
    }
}
