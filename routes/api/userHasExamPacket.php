<?php

use App\Http\Controllers\WelderHasExamPacketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('user-exam-packet')->group(function () {
    Route::get('/', [WelderHasExamPacketController::class, 'index'])->name('api.user.exam.packet.index');
    Route::get('/check/{examPacketId}', [WelderHasExamPacketController::class, 'check'])->name('api.user.exam.packet.check');
    Route::post('/', [WelderHasExamPacketController::class, 'store'])->name('api.user.exam.packet.store');
    Route::post('/key-check', [WelderHasExamPacketController::class, 'keyCheck'])->name('api.user.exam.packet.key.check');
    Route::post('/insert-value-practice', [WelderHasExamPacketController::class, 'insertValuePactice'])->name('api.user.exam.packet.insert.value.practice');
    Route::post('/punishment', [WelderHasExamPacketController::class, 'punishment'])->name('api.user.exam.packet.update.punishment');
    Route::post('/update-penalty', [WelderHasExamPacketController::class, 'updatePenalty'])->name('api.user.exam.packet.update.penalty');
    Route::post('/update-key-packet', [WelderHasExamPacketController::class, 'updateKeyPacket'])->name('api.user.exam.packet.update.key.packet');
    Route::put('/update-evaluation/{id}', [WelderHasExamPacketController::class, 'updateEvaluation'])->name('api.user.exam.packet.update.evaluation');
});
