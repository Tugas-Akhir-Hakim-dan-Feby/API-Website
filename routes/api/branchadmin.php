<?php

use App\Http\Controllers\BranchadminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('branchadmin')->group(function () {
    Route::get('/', [BranchadminController::class, 'index'])->name('api.branchadmin.index');
    Route::post('/', [BranchadminController::class, 'store'])->name('api.branchadmin.store');
    Route::get('/{id}', [BranchadminController::class, 'show'])->name('api.branchadmin.show');
    Route::put('/{id}', [BranchadminController::class, 'update'])->name('api.branchadmin.update');
    Route::delete('/{id}', [BranchadminController::class, 'destroy'])->name('api.branchadmin.destroy');
});
