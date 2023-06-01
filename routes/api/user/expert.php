<?php

use App\Http\Controllers\User\ExpertController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/expert')->group(function () {
    Route::get('/', [ExpertController::class, 'index'])->name('api.user.expert.index');
    Route::post('/', [ExpertController::class, 'store'])->name('api.user.expert.store');
    Route::post('/upload-excel', [ExpertController::class, 'uploadExcel'])->name('api.user.upload.excel');
    Route::get('/{id}', [ExpertController::class, 'show'])->name('api.user.expert.show');
    Route::put('/{id}', [ExpertController::class, 'update'])->name('api.user.expert.update');
    Route::delete('/{id}', [ExpertController::class, 'destroy'])->name('api.user.expert.destroy');
    Route::put('/confirmation/{id}', [ExpertController::class, 'confirmation'])->name('api.user.expert.confirmation');
    Route::put('/update-status/{id}', [ExpertController::class, 'updateStatus'])->name('api.user.expert.update.status');
});
