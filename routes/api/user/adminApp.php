<?php

use App\Http\Controllers\User\AdminAppController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user/admin-app')->group(function () {
    Route::get('/', [AdminAppController::class, 'index'])->name('api.user.admin.app.index');
    Route::post('/', [AdminAppController::class, 'store'])->name('api.user.admin.app.store');
    Route::get('/{id}', [AdminAppController::class, 'show'])->name('api.user.admin.app.show');
    Route::put('/{id}', [AdminAppController::class, 'update'])->name('api.user.admin.app.update');
    Route::delete('/{id}', [AdminAppController::class, 'destroy'])->name('api.user.admin.app.destroy');
});
