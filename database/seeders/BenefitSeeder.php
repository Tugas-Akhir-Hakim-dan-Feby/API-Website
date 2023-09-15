<?php

namespace Database\Seeders;

use App\Models\BenefitMember;
use App\Models\Cost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefits[Cost::WELDER_MEMBER] = [
            "Mendapatkan Informasi Terbaru Pengelasan",
            "Mendapatkan Informasi Lowongan Pekerjaan",
            "Mendapatkan Konsultasi bersama Pakar IWS",
        ];

        $benefits[Cost::COMPANY_MEMBER] = [
            "Mendapatkan Informasi Terbaru Pengelasan",
            "Mendapatkan Konsultasi bersama Pakar IWS",
            "Menyajikan Berita terkait Perusahaan",
        ];

        $benefits[Cost::TEST_INSTITUTION] = [
            "Mendapatkan Informasi Terbaru Pengelasan",
            "Dapat mengadakan pelatihan kerja",
            "Dapat berkolaborasi dengan perusahaan hingga organisasi lainnya",
        ];

        foreach ($benefits as $key => $value) {
            foreach ($value as $benefit) {
                BenefitMember::create([
                    "cost_id" => $key,
                    "description" => $benefit
                ]);
            }
        }
    }
}
