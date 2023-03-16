<?php
use App\Http\Controllers\WelderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('skill/welder')->group(function () {
    Route::get('/', [WelderController::class, 'index'])->name('api.skill.welder.index');
    Route::post('/', [WelderController::class, 'store'])->name('api.skill.welder.store');
    Route::get('/{id}', [WelderController::class, 'show'])->name('api.skill.welder.show');
    Route::put('/{id}', [WelderController::class, 'update'])->name('api.skill.welder.update');
    Route::delete('/{id}', [WelderController::class, 'destroy'])->name('api.skill.welder.destroy');
});
