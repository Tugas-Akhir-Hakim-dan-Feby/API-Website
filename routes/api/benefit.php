<?php

use App\Http\Controllers\CostBenefitController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('cost-benefit')->group(function () {
    Route::post('/', [CostBenefitController::class, 'store'])->name('api.cost.benefit.store');
    Route::delete('/{id}', [CostBenefitController::class, 'destroy'])->name('api.cost.benefit.destroy');
});
