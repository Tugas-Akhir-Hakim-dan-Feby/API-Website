<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\User\CompanyMember\Role as CompanyMemberRole;
use App\Http\Filters\User\CompanyMember\Search;
use App\Http\Requests\User\CompanyMember\CompanyRequestStore;
use App\Http\Resources\User\CompanyMemberCollection;
use App\Http\Resources\User\CompanyMemberDetail;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class CompanyMemberController extends Controller
{
    use MessageFixer, UploadDocument, PaymentFixer;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $companyMembers = app(Pipeline::class)
            ->send($this->userRepository->query())
            ->through([
                CompanyMemberRole::class,
                Search::class
            ])
            ->thenReturn()
            ->paginate($request->per_page);

        return new CompanyMemberCollection($companyMembers);
    }

    public function create()
    {
        //
    }

    public function store(CompanyRequestStore $request)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid, 'role_id' => User::GUEST]);
        if (!$user) {
            abort(404);
        }

        $role = Role::findById(User::MEMBER_COMPANY, 'api');

        $request->merge([
            'uuid' => Str::uuid(),
        ]);

        try {
            if ($request->hasFile("document_company_legality")) {
                $request->merge([
                    "company_legality" => $this->storageFile($request->file("document_company_legality"), "company_legality")
                ]);
            }

            $this->pay();

            $user->update([
                "role_id" => $role->id
            ]);
            $user->companyMember()->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->payment);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function show($id)
    {
        $companyMember = $this->userRepository->findOrFail($id);
        if (!$companyMember) {
            abort(404);
        }

        $companyMember->load(["companyMember"]);

        return new CompanyMemberDetail($companyMember);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
