<?php

use App\Http\Controllers\User\UpdatePasswordController;
use App\Http\Controllers\User\WelderMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::get('/me', [UserController::class, 'me'])->name('api.user.me');
    Route::get('/{id}', [UserController::class, 'show'])->name('api.user.show');
    Route::post('/update-password', UpdatePasswordController::class)->name('api.user.update.password');
    Route::put('/upload-image/{id}', [UserController::class, 'uploadImage'])->name('api.user.upload.image');

    Route::prefix('welder-member')->group(function () {
        Route::get('/', [WelderMemberController::class, 'index'])->name('api.user.welder.member.index');
        Route::post('/', [WelderMemberController::class, 'store'])->name('api.user.welder.member.store');
        Route::post('/upload-excel', [WelderMemberController::class, 'uploadExcel'])->name('api.user.welder.member.upload.excel');
        Route::get('/{id}', [WelderMemberController::class, 'show'])->name('api.user.welder.member.show');
        Route::put('/{id}', [WelderMemberController::class, 'update'])->name('api.user.welder.member.update');
        Route::delete('/{id}', [WelderMemberController::class, 'destroy'])->name('api.user.welder.member.destroy');
        Route::put('/update-status/{id}', [WelderMemberController::class, 'updateStatus'])->name('api.user.welder.member.update.status');
    });
});
