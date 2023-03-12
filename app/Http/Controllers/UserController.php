<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UploadImageRequest;
use App\Http\Traits\UploadDocument;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use UploadDocument;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show()
    {
    }

    public function uploadImage(UploadImageRequest $request, $id)
    {
        $user = $this->userRepository->findByCriteria(['uuid' => $id]);

        if (!$user) {
            abort(404);
        }

        return DB::transaction(function () use ($request, $user) {
            if ($user->document) {
                $path = str_replace(url('storage') . '/', '', $user->document->document_path);
                Storage::delete($path);

                $user->document()->delete();
            }

            return $this->upload($request->image, $user->document(), 'user');
        });
    }
}
