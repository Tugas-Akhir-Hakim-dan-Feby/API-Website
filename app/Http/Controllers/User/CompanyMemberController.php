<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Facades\PaymentFacade;
use App\Http\Filters\User\CompanyMember\Role as CompanyMemberRole;
use App\Http\Filters\User\CompanyMember\Search;
use App\Http\Requests\User\CompanyMember\CompanyRequestStore;
use App\Http\Requests\User\CompanyMember\CompanyRequestUpdate;
use App\Http\Requests\User\CompanyMember\UpdateDocumentRequest;
use App\Http\Requests\User\CompanyMember\UpdateLogoRequest;
use App\Http\Requests\User\CompanyMember\UploadFileRequest;
use App\Http\Resources\User\CompanyMemberCollection;
use App\Http\Resources\User\CompanyMemberDetail;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\PaymentFixer;
use App\Http\Traits\UploadDocument;
use App\Imports\User\CompanyMemberImport;
use App\Models\Cost;
use App\Models\User;
use App\Repositories\Cost\CostRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

class CompanyMemberController extends Controller
{
    use MessageFixer, UploadDocument, PaymentFixer;

    protected $userRepository, $paymentFacade, $costRepository;

    public function __construct(UserRepository $userRepository = null, PaymentFacade $paymentFacade, CostRepository $costRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentFacade = $paymentFacade;
        $this->costRepository = $costRepository;
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

    public function all(Request $request)
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

        $user = $this->userRepository->findByCriteria(['uuid' => Auth::user()->uuid]);
        if (!$user) {
            return $this->warningMessage("pengguna sudah terdaftar menjadi perusahaan member");
        }

        $cost = $this->costRepository->find(Cost::COMPANY_MEMBER);
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

            if ($request->hasFile("document_company_logo")) {
                $request->merge([
                    "company_logo" => $this->storageFile($request->file("document_company_logo"), "company_logo")
                ]);
            }

            $this->paymentFacade->payToCompanyMember($cost, url('invoice/success'));

            $user->update([
                "role_id" => $role->id,
                'membership_card' => $this->numberMembership($user->id)
            ]);
            $user->companyMember()->create($request->all());

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user->payment);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function uploadExcel(UploadFileRequest $request)
    {
        DB::beginTransaction();

        try {
            DB::commit();
            Excel::import(new CompanyMemberImport, $request->file('file'));

            return $this->successMessage('data berhasil ditambahkan', []);
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

    public function update(CompanyRequestUpdate $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($id);
        if (!$user) {
            abort(404);
        }

        try {
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
            ]);

            $user->companyMember()->update($request->except(["name", "email", "id", "membership_card"]));

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateDocument(UpdateDocumentRequest $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($id);
        if (!$user) {
            abort(404);
        }

        try {
            if ($user->companyMember->company_legality) {
                $path = str_replace(url('storage') . '/', '', $user->companyMember->company_legality);
                Storage::delete($path);
            }

            $user->companyMember()->update([
                "company_legality" => $request->file('document')->store('company_legality')
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateLogo(UpdateLogoRequest $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findOrFail($id);
        if (!$user) {
            abort(404);
        }

        try {
            if ($user->companyMember->company_logo) {
                $path = str_replace(url('storage') . '/', '', $user->companyMember->company_logo);
                Storage::delete($path);
            }

            $user->companyMember()->update([
                "company_logo" => $request->file('logo')->store('company_logo')
            ]);

            DB::commit();
            return $this->successMessage("data berhasil diperbaharui", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->companyMember?->company_legality) {
                $path = str_replace(url('storage') . '/', '', $user->companyMember?->company_legality);
                Storage::delete($path);
            }

            if ($user->companyMember?->company_logo) {
                $path = str_replace(url('storage') . '/', '', $user->companyMember?->company_logo);
                Storage::delete($path);
            }

            if ($user->companyMember) {
                $user->companyMember()->delete();
            }

            if ($user->articles) {
                foreach ($user->articles as $article) {
                    if ($article->document) {
                        $path = str_replace(url('storage') . '/', '', $article->document->document_path);
                        Storage::delete($path);

                        $article->document()->delete();
                    }
                }

                $user->articles()->delete();
            }

            $user->removeRole(Role::findById(User::MEMBER_COMPANY, 'api'));
            $user->update(["role_id", User::MEMBER_APPLICATION]);

            DB::commit();
            return $this->successMessage("data berhasil dihapus", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
