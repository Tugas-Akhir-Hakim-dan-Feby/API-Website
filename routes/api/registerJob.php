<?php

use App\Http\Controllers\RegisterJobController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('register-job')->group(function () {
    Route::get('/', [RegisterJobController::class, 'index'])->name('api.register.job.index');
    Route::post('/', [RegisterJobController::class, 'store'])->name('api.register.job.store');
    Route::post('/accept', [RegisterJobController::class, 'accept'])->name('api.register.job.accept');
    Route::post('/reject', [RegisterJobController::class, 'reject'])->name('api.register.job.reject');
    Route::get('/check/{id}', [RegisterJobController::class, 'check'])->name('api.register.job.check');
    Route::get('/{id}', [RegisterJobController::class, 'show'])->name('api.register.job.show');
});
