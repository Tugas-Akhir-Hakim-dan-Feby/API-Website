<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UploadImageRequest;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\UploadDocument;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UploadDocument, MessageFixer;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function me()
    {
        $user = Auth::user();

        $data = [
            'user' => $user,
            'roles' => $user->roles->pluck('name')
        ];

        if ($user->isMemberWelder()) {
            $data["skill"] = $user->welderMember->welderSkill;
        }

        return response()->json($data);
    }

    public function show()
    {
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
