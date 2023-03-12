<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/{id}', [UserController::class, 'show'])->name('api.user.show');
    Route::put('/upload-image/{id}', [UserController::class, 'uploadImage'])->name('api.user.upload.image');
});
