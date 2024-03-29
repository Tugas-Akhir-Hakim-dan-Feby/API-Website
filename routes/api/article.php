<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;


Route::prefix('article')->group(function () {
    Route::get('/all', [ArticleController::class, 'all'])->name('api.article.all');
    Route::get('/show-by-slug/{slug}', [ArticleController::class, 'showBySlug'])->name('api.article.show.by.slug');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('api.article.index');
        Route::post('/', [ArticleController::class, 'store'])->name('api.article.store');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('api.article.show');
        Route::put('/update-status/{id}', [ArticleController::class, 'updateStatus'])->name('api.article.update.status');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('api.article.update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('api.article.destroy');
    });
});
