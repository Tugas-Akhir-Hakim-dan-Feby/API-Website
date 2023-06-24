<?php

use App\Http\Controllers\CostController;
use Illuminate\Support\Facades\Route;


Route::prefix('cost')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [CostController::class, 'index'])->name('api.cost.index');
        Route::put('/{id}', [CostController::class, 'update'])->name('api.cost.update');
    });

    Route::get('/{id}', [CostController::class, 'show'])->name('api.cost.show');
});
