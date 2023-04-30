<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\WelderMember\Role;
use App\Http\Requests\User\WelderMember\WelderRequestStore;
use App\Http\Resources\User\WelderMemberCollection;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Models\User;
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
    use MessageFixer, UploadDocument;

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
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid, 'role_id' => User::GUEST]);
        if (!$user) {
            abort(404);
        }

        $role = PermissionModelsRole::findById(User::MEMBER_WELDER, 'api');
        $welderSkill = $this->welderSkillRepository->findOrFail($request->welder_skill_id);

        $request->merge([
            'uuid' => Str::uuid(),
            'status' => WelderMember::INACTIVE,
            'welder_skill_id' => $welderSkill->id
        ]);


        try {
            if ($request->hasFile("document_certificate_school")) {
                $request->merge([
                    "certificate_school" => $this->storageFile($request->file("document_certificate_school"), "cerfiticate_school")
                ]);
            }

            if ($request->hasFile("document_pas_photo")) {
                $request->merge([
                    "pas_photo" => $this->storageFile($request->file("document_pas_photo"), "pas_photo")
                ]);
            }

            if ($request->hasFile("document_certificate_competency")) {
                $this->upload($request->file("document_certificate_competency"), $user->welderDocuments(), "welder_document");
            }

            $user->syncRoles($role);
            $user->welderMember()->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }

        // try {
        //     $secret_key = 'Basic ' . config('xendit.key_auth');
        //     $external_id = Str::random(10);
        //     $data_request = Http::withHeaders([
        //         'Authorization' => $secret_key
        //     ])->post('https://api.xendit.co/v2/invoices', [
        //         'external_id' => $external_id,
        //         'amount' => 100000
        //     ]);
        //     $response = $data_request->object();

        //     $user->payment()->create([
        //         'uuid' => Str::uuid(),
        //         'user_id' => Auth::user()->id,
        //         'external_id' => $external_id,
        //         'description' => 'pembayaran welder member',
        //         'amount' => 100000,
        //         'payment_link' => $response->invoice_url,
        //         'status' => $response->status,
        //     ]);
        //     $user->syncRoles($role);
        //     $user->welderMember()->create($request->all());

        //     DB::commit();
        //     return $this->createMessage("data berhasil ditambahkan", $user);
        // } catch (\Throwable $th) {
        //     DB::rollback();
        //     return $this->errorMessage($th->getMessage());
        // }
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
