<?php

use App\Http\Controllers\User\OperatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/operator')->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('api.user.operator.index');
    Route::post('/', [OperatorController::class, 'store'])->name('api.user.operator.store');
    Route::get('/{id}', [OperatorController::class, 'show'])->name('api.user.operator.show');
    Route::put('/{id}', [OperatorController::class, 'update'])->name('api.user.operator.update');
    Route::delete('/{id}', [OperatorController::class, 'destroy'])->name('api.user.operator.destroy');
});
