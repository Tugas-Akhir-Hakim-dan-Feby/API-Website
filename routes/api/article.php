<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('article')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('api.article.index');
    Route::post('/', [ArticleController::class, 'store'])->name('api.article.store');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('api.article.show');
    Route::put('/{id}', [ArticleController::class, 'update'])->name('api.article.update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('api.article.destroy');
});
