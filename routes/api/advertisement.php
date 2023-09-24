<?php

use App\Http\Controllers\AdvertisementController;
use Illuminate\Support\Facades\Route;


Route::prefix('advertisement')->group(function () {
    Route::get('/all', [AdvertisementController::class, 'all'])->name('api.advertisement.all');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [AdvertisementController::class, 'index'])->name('api.advertisement.index');
        Route::post('/', [AdvertisementController::class, 'store'])->name('api.advertisement.store');
        Route::get('/{id}', [AdvertisementController::class, 'show'])->name('api.advertisement.show');
        Route::put('/{id}', [AdvertisementController::class, 'update'])->name('api.advertisement.update');
        Route::delete('/{id}', [AdvertisementController::class, 'destroy'])->name('api.advertisement.destroy');
    });
});
