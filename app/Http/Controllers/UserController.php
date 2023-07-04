<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UploadImageRequest;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UploadDocument, MessageFixer;

    protected $userRepository, $paymentRepository;

    public function __construct(UserRepository $userRepository, PaymentRepository $paymentRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function me()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
            'roles' => $user->roles->pluck('name')
        ];

        if ($user->isMemberWelder() || $user->isExpert()) {
            $data["skill"] = $user->welderMember->welderSkill;
            $user->load(['welderMember.welderSkill', 'welderDocuments']);
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
}
