<?php

use App\Http\Controllers\User\WelderMemberController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->prefix('user/welder-member')->group(function () {
    Route::get('/', [WelderMemberController::class, 'index'])->name('api.user.welder.member.index');
    Route::post('/', [WelderMemberController::class, 'store'])->name('api.user.welder.member.store');
    Route::post('/upload-excel', [WelderMemberController::class, 'uploadExcel'])->name('api.user.welder.member.upload.excel');
    Route::get('/{id}', [WelderMemberController::class, 'show'])->name('api.user.welder.member.show');
    Route::put('/{id}', [WelderMemberController::class, 'update'])->name('api.user.welder.member.update');
    Route::delete('/{id}', [WelderMemberController::class, 'destroy'])->name('api.user.welder.member.destroy');
    Route::put('/update-status/{id}', [WelderMemberController::class, 'updateStatus'])->name('api.user.welder.member.update.status');
});
