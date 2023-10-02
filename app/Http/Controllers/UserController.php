<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UploadImageRequest;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Models\WelderDocument;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use UploadDocument, MessageFixer;

    protected $userRepository, $paymentRepository, $welderDocument;

    public function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository, WelderDocument $welderDocument)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
        $this->welderDocument = $welderDocument;
    }

    public function me()
    {
        $user = $this->userRepository->findOrFail(Auth::user()->uuid);

        $data = [
            'user' => $user,
            'roles' => $user->roles->pluck('name')
        ];

        if ($user->isMemberWelder() || $user->isExpert()) {
            $user->load(['welderMember', 'welderDocuments', 'welderHasSkills.welderSkill', 'personalData']);
        }

        if ($user->isExpert()) {
            $user->load("expert");
        }

        if ($user->isAdminHub()) {
            $user->load("adminHub");
        }

        if ($user->isAdminBranch()) {
            $user->load("adminBranch.branch");
        }

        if ($user->isMemberCompany()) {
            $user->load("companyMember");
        }

        if ($user->isOperator()) {
            $user->load("operator");
        }

        if ($user->document) {
            $data["document"] = $user->document;
        }

        return response()->json($data);
    }

    public function show()
    {
    }

    public function checkPayments()
    {
        return DB::transaction(function () {
            $this->paymentRepository->paymentUsers();

            return $this->successMessage("data berhasil", []);
        });
    }

    public function uploadImage(UploadImageRequest $request, $id)
    {
        DB::beginTransaction();

        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        try {
            if ($user->document) {
                $path = str_replace(url('storage') . '/', '', $user->document->document_path);
                Storage::delete($path);

                $user->document()->delete();
            }

            $this->upload($request->image, $user->document(), 'user');

            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function uploadCertificate(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            "files" => "required|array",
            "files.*" => "required|file|mimes:pdf"
        ]);

        if ($validator->fails()) {
            return $this->warningMessage($validator->errors());
        }

        $user = $this->userRepository->find(auth()->user()->id);

        try {
            foreach ($request->file('files') as $file) {
                $this->upload($file, $user->welderDocuments(), "certificates");
            }
            DB::commit();
            return $this->createMessage("data berhasil ditambahkan", $user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }

    public function deleteCertificate($id)
    {
        DB::beginTransaction();

        $welderDocument = $this->welderDocument->findOrFail($id);

        try {
            $path = str_replace(url('storage') . '/', '', $welderDocument->document_path);
            Storage::delete($path);

            $welderDocument->delete();

            DB::commit();
            return $this->createMessage("data berhasil dihapus", $welderDocument);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
