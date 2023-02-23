<?php

use App\Http\Controllers\Skill\ExpertController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('skill/expert')->group(function () {
    Route::get('/', [ExpertController::class, 'index'])->name('api.skill.expert.index');
    Route::post('/', [ExpertController::class, 'store'])->name('api.skill.expert.store');
    Route::get('/{id}', [ExpertController::class, 'show'])->name('api.skill.expert.show');
    Route::put('/{id}', [ExpertController::class, 'update'])->name('api.skill.expert.update');
    Route::delete('/{id}', [ExpertController::class, 'destroy'])->name('api.skill.expert.destroy');
});
