<?php

use App\Http\Controllers\HubController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('hub')->group(function () {
    Route::get('/', [HubController::class, 'index'])->name('api.hub.index');
    Route::post('/', [HubController::class, 'store'])->name('api.hub.store');
    Route::get('/{id}', [HubController::class, 'show'])->name('api.hub.show');
    Route::put('/{id}', [HubController::class, 'update'])->name('api.hub.update');
    Route::delete('/{id}', [HubController::class, 'destroy'])->name('api.hub.destroy');
});
