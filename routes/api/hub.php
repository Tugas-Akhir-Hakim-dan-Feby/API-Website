<?php

use App\Http\Controllers\HubController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/hub')->group(function () {
    Route::get('/', [HubController::class, 'index'])->name('api.user.hub.index');
    Route::post('/', [HubController::class, 'store'])->name('api.user.hub.store');
    Route::get('/{id}', [HubController::class, 'show'])->name('api.user.hub.show');
    Route::put('/{id}', [HubController::class, 'update'])->name('api.user.hub.update');
    Route::delete('/{id}', [HubController::class, 'destroy'])->name('api.user.hub.destroy');
});
