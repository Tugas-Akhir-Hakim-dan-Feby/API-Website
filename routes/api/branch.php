<?php

use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('api.branch.index');
    Route::post('/', [BranchController::class, 'store'])->name('api.branch.store');
    Route::get('/{id}', [BranchController::class, 'show'])->name('api.branch.show');
    Route::put('/{id}', [BranchController::class, 'update'])->name('api.branch.update');
    Route::delete('/{id}', [BranchController::class, 'destroy'])->name('api.branch.destroy');
});
