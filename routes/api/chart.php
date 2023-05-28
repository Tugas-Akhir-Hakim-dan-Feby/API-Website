<?php

use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('chart')->group(function () {
    Route::get('/skill', [ChartController::class, 'skillChart'])->name('api.home.skill.chart');
});
