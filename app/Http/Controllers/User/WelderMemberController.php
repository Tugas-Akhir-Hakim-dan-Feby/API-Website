<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\WelderMember\Role;
use App\Http\Requests\User\WelderMember\WelderRequestStore;
use App\Http\Resources\User\WelderMemberCollection;
use App\Models\Role as ModelsRole;
use App\Models\User\WelderMember;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\UserWelderMember\UserWelderMemberRepository;
use App\Repositories\WelderSkill\WelderSkillRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role as PermissionModelsRole;

class WelderMemberController extends Controller
{
    protected $welderMemberRepository, $userRepository, $welderSkillRepository, $paymentRepository;

    public function __construct(
        UserWelderMemberRepository $welderMemberRepository,
        UserRepository $userRepository,
        WelderSkillRepository $welderSkillRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->welderMemberRepository = $welderMemberRepository;
        $this->userRepository = $userRepository;
        $this->welderSkillRepository = $welderSkillRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request)
    {
        $welderMembers = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                Role::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new WelderMemberCollection($welderMembers);
    }

    public function store(WelderRequestStore $request)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid, 'role_id' => ModelsRole::GUEST]);
        if (!$user) {
            abort(404);
        }

        $role = PermissionModelsRole::findById(ModelsRole::MEMBER_WELDER, 'api');
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            'uuid' => Str::uuid(),
            'status' => WelderMember::INACTIVE,
            'welder_skill_id' => $welderSkill->id
        ]);

        return DB::transaction(function () use ($request, $user, $role) {
            $secret_key = 'Basic ' . config('xendit.key_auth');
            $external_id = Str::random(10);
            $data_request = Http::withHeaders([
                'Authorization' => $secret_key
            ])->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $external_id,
                'amount' => 100000
            ]);
            $response = $data_request->object();

            $this->paymentRepository->create([
                'uuid' => Str::uuid(),
                'user_id' => Auth::user()->id,
                'external_id' => $external_id,
                'description' => 'pembayaran welder member',
                'amount' => 100000,
                'payment_link' => $response->invoice_url,
                'status' => $response->status,
            ]);
            $user->syncRoles($role);
            return $user->welderMember()->create($request->all());
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\WelderMember  $welderMember
     * @return \Illuminate\Http\Response
     */
    public function show(WelderMember $welderMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\WelderMember  $welderMember
     * @return \Illuminate\Http\Response
     */
    public function edit(WelderMember $welderMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\WelderMember  $welderMember
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WelderMember $welderMember)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\WelderMember  $welderMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(WelderMember $welderMember)
    {
        //
    }
}
