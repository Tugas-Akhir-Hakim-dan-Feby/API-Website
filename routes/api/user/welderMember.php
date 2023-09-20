<?php

use App\Http\Controllers\User\UpdatePasswordController;
use App\Http\Controllers\User\WelderMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->group(function () {
    Route::get('/check-payment', [UserController::class, 'checkPayments'])->name('api.user.check.payments');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix('welder-member')->group(function () {
            Route::get('/', [WelderMemberController::class, 'index'])->name('api.user.welder.member.index');
            Route::post('/', [WelderMemberController::class, 'store'])->name('api.user.welder.member.store');
            Route::post('/store-member', [WelderMemberController::class, 'storeMember'])->name('api.user.welder.member.store.member');
            Route::post('/upload-excel', [WelderMemberController::class, 'uploadExcel'])->name('api.user.welder.member.upload.excel');
            Route::get('/{id}', [WelderMemberController::class, 'show'])->name('api.user.welder.member.show');
            Route::put('/update-document/{id}', [WelderMemberController::class, 'updateDocument'])->name('api.user.welder.member.update.document');
            Route::put('/{id}', [WelderMemberController::class, 'update'])->name('api.user.welder.member.update');
            Route::delete('/delete-skill/{id}', [WelderMemberController::class, 'deleteSkill'])->name('api.user.welder.member.delete.skill');
            Route::delete('/{id}', [WelderMemberController::class, 'destroy'])->name('api.user.welder.member.destroy');
            Route::put('/update-status/{id}', [WelderMemberController::class, 'updateStatus'])->name('api.user.welder.member.update.status');
            Route::put('/update-pas-photo/{id}', [WelderMemberController::class, 'updatePasPhoto'])->name('api.user.welder.member.update.pas.photo');
            Route::put('/update-curriculum-vitae/{id}', [WelderMemberController::class, 'updateCurriculumVitae'])->name('api.user.welder.member.update.curriculum.vitae');
        });

        Route::get('/me', [UserController::class, 'me'])->name('api.user.me');
        Route::get('/{id}', [UserController::class, 'show'])->name('api.user.show');
        Route::post('/update-password', UpdatePasswordController::class)->name('api.user.update.password');
        Route::put('/upload-image/{id}', [UserController::class, 'uploadImage'])->name('api.user.upload.image');
    });
});
