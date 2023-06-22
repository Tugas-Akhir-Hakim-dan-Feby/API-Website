<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('payment')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('api.payment.index');
    });

    Route::post('callback', [PaymentController::class, 'callback'])->name('api.payment.callback');
    Route::get('/{externalId}', [PaymentController::class, 'show'])->name('api.payment.show');
});
