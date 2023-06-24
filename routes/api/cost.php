<?php

use App\Http\Controllers\CostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('cost')->group(function () {
    Route::get('/', [CostController::class, 'index'])->name('api.cost.index');
    Route::get('/{id}', [CostController::class, 'show'])->name('api.cost.show');
    Route::put('/{id}', [CostController::class, 'update'])->name('api.cost.update');
});
