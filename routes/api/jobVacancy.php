<?php

use App\Http\Controllers\JobVacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('job-vacancy')->group(function () {
    Route::get('/', [JobVacancyController::class, 'index'])->name('api.job.vacancy.index');
    Route::post('/', [JobVacancyController::class, 'store'])->name('api.job.vacancy.store');
    Route::get('/show-by-slug/{id}', [JobVacancyController::class, 'showBySlug'])->name('api.job.vacancy.show.by.slug');
    Route::get('/{id}', [JobVacancyController::class, 'show'])->name('api.job.vacancy.show');
    Route::put('/{id}', [JobVacancyController::class, 'update'])->name('api.job.vacancy.update');
    Route::delete('/{id}', [JobVacancyController::class, 'destroy'])->name('api.job.vacancy.destroy');
});
