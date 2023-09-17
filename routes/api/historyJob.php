<?php

use App\Http\Controllers\HistoryJobController;
use Illuminate\Support\Facades\Route;

Route::get('history-job', HistoryJobController::class)
    ->name('api.history.job.index')
    ->middleware('auth:sanctum');
