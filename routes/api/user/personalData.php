<?php

use App\Http\Controllers\PersonalDataController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/personal-data')->group(function () {
    Route::post('/', [PersonalDataController::class, 'store'])->name('api.user.personal.data.store');
    Route::get('/{id}', [PersonalDataController::class, 'show'])->name('api.user.personal.data.show');
});
