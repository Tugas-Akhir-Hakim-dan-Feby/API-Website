<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillUserRelationToBranch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-it-user-branch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', 'admin.cabang@mailinator.com')->first();

        if (!$user->adminBranch) {
            $branch = Branch::create([
                'uuid' => Str::uuid(),
                'branch_name' => "Chung Jui Fang",
                'branch_address' => "Jawa Tengah",
                'branch_phone' => "12121221",
            ]);

            $user->adminBranch()->create([
                'uuid' => Str::uuid(),
                'nip' => '121212',
                'position' => 'CEO',
                'phone' => '12121212',
                'address' => 'Indramayu',
                'date_birth' => Carbon::now(),
                'birth_place' => 'Indramayu',
                'status' => 1,
                'branch_id' => $branch->id
            ]);

            $this->info('relation created successfully...!');
        } else {
            $this->error('relation already exist...!');
        }
    }
}
