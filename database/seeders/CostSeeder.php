<?php

namespace Database\Seeders;

use App\Models\Cost;
use Database\Seeders\Traits\DisableForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CostSeeder extends Seeder
{
    use DisableForeignKey, TruncateTable;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        $this->truncate('costs');

        $costs = [
            [
                'type_price' => 'Harga Welder Member',
                'nominal_price' => '150000',
                'uuid' => Str::uuid()
            ],
            [
                'type_price' => 'Harga Perusahaan Member',
                'nominal_price' => '5000000',
                'uuid' => Str::uuid()
            ],
            [
                'type_price' => 'Harga Iklan',
                'nominal_price' => '100000',
                'uuid' => Str::uuid()
            ],
            [
                'type_price' => 'Harga LPK',
                'nominal_price' => '100000',
                'uuid' => Str::uuid()
            ],
            [
                'type_price' => 'Harga Ujian',
                'nominal_price' => '100000',
                'uuid' => Str::uuid()
            ],
        ];

        foreach ($costs as $cost) {
            Cost::create($cost);
        }
    }
}
