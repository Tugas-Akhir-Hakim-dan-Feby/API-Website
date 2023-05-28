<?php

use App\Http\Controllers\WelderAnswerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('welder-answer')->group(function () {
    Route::get('/', [WelderAnswerController::class, 'index'])->name('api.welder.answer.index');
    Route::get('/correct-answer/{examPacketId}', [WelderAnswerController::class, 'correctAnswer'])->name('api.welder.correct.answer');
    Route::post('/', [WelderAnswerController::class, 'store'])->name('api.welder.answer.store');
    Route::get('/{id}', [WelderAnswerController::class, 'show'])->name('api.welder.answer.show');
    Route::put('/{id}', [WelderAnswerController::class, 'update'])->name('api.welder.answer.update');
    Route::delete('/{id}', [WelderAnswerController::class, 'destroy'])->name('api.welder.answer.destroy');
});
