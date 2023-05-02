<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('payment')->group(function () {
    Route::post('callback', [PaymentController::class, 'callback'])->name('api.payment.callback');
    Route::get('/{externalId}', [PaymentController::class, 'show'])->name('api.payment.show');
});
