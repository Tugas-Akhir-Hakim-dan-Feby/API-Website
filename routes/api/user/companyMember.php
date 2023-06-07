<?php

use App\Http\Controllers\User\CompanyMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/user/company-member', [CompanyMemberController::class, 'index'])->name('api.user.company.member.index');

Route::middleware(['auth:sanctum'])->prefix('user/company-member')->group(function () {
    Route::post('/', [CompanyMemberController::class, 'store'])->name('api.user.company.member.store');
    Route::post('/upload-excel', [CompanyMemberController::class, 'uploadExcel'])->name('api.company.member.upload.excel');
    Route::get('/{id}', [CompanyMemberController::class, 'show'])->name('api.user.company.member.show');
    Route::put('/{id}', [CompanyMemberController::class, 'update'])->name('api.user.company.member.update');
    Route::delete('/{id}', [CompanyMemberController::class, 'destroy'])->name('api.user.company.member.destroy');
    Route::put('/update-status/{id}', [CompanyMemberController::class, 'updateStatus'])->name('api.user.company.member.update.status');
    Route::put('/update-document/{id}', [CompanyMemberController::class, 'updateDocument'])->name('api.user.company.member.update.document');
});
