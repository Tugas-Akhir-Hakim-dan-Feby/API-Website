<?php

use App\Http\Controllers\ExamPacketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('exam-packet')->group(function () {
    Route::get('/', [ExamPacketController::class, 'index'])->name('api.exam.packet.index');
    Route::post('/', [ExamPacketController::class, 'store'])->name('api.exam.packet.store');
    Route::post('/upload-evaluation/{id}', [ExamPacketController::class, 'uploadEvaluation'])->name('api.exam.packet.upload.evaluation');
    Route::get('/{id}', [ExamPacketController::class, 'show'])->name('api.exam.packet.show');
    Route::put('/update-status/{id}', [ExamPacketController::class, 'updateStatus'])->name('api.exam.packet.update.status');
    Route::put('/update-certificate/{id}', [ExamPacketController::class, 'updateCertificate'])->name('api.exam.packet.update.certificate');
    Route::put('/{id}', [ExamPacketController::class, 'update'])->name('api.exam.packet.update');
    Route::delete('/{id}', [ExamPacketController::class, 'destroy'])->name('api.exam.packet.destroy');
});
