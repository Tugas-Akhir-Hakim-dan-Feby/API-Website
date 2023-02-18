<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class)->name('api.auth.register');

    Route::post('/login', LoginController::class)->name('api.auth.login');

    Route::post('/new-password', NewPasswordController::class)->name('api.auth.new.password');
});
