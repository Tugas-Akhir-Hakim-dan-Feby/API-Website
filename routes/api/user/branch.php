<?php

use App\Http\Controllers\User\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('api.user.branch.index');
    Route::post('/', [BranchController::class, 'store'])->name('api.user.branch.store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('api.user.branch.show');
    Route::put('/{id}', [BranchController::class, 'update'])->name('api.user.branch.update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('api.user.branch.destroy');
    Route::put('/update-status/{id}', [BranchController::class, 'updateStatus'])->name('api.user.branch.update.status');
});
