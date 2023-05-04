<?php

use App\Http\Controllers\User\UpdatePasswordController;
use App\Http\Controllers\User\UpdateProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user')->group(function () {
    Route::post('/update-password', UpdatePasswordController::class)->name('api.user.update.password');
    Route::post('/update-profile', UpdateProfileController::class)->name('api.user.update.profile');
});
