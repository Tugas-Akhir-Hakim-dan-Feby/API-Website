<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('exam')->group(function () {
    Route::get('/', [ExamController::class, 'index'])->name('api.exam.index');
    Route::post('/import', [ExamController::class, 'import'])->name('api.exam.import');
    Route::post('/', [ExamController::class, 'store'])->name('api.exam.store');
    Route::get('/{id}', [ExamController::class, 'show'])->name('api.exam.show');
    Route::put('/{id}', [ExamController::class, 'update'])->name('api.exam.update');
    Route::delete('/{id}', [ExamController::class, 'destroy'])->name('api.exam.destroy');
});
