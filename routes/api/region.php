<?php

use App\Http\Controllers\Region\DistrictController;
use App\Http\Controllers\Region\PostalCodeController;
use App\Http\Controllers\Region\ProvinceController;
use App\Http\Controllers\Region\RegencyController;
use App\Http\Controllers\Region\VillageController;
use Illuminate\Support\Facades\Route;

Route::prefix('province')->group(function () {
    Route::get('/', [ProvinceController::class, 'index'])->name('api.province.index');
    Route::get('/{id}', [ProvinceController::class, 'show'])->name('api.province.show');
});

Route::prefix('regency')->group(function () {
    Route::get('/', [RegencyController::class, 'index'])->name('api.regency.index');
    Route::get('/{id}', [RegencyController::class, 'show'])->name('api.regency.show');
});

Route::prefix('district')->group(function () {
    Route::get('/', [DistrictController::class, 'index'])->name('api.district.index');
    Route::get('/{id}', [DistrictController::class, 'show'])->name('api.district.show');
});

Route::prefix('village')->group(function () {
    Route::get('/', [VillageController::class, 'index'])->name('api.village.index');
    Route::get('/{id}', [VillageController::class, 'show'])->name('api.village.show');
});

Route::prefix('postal-code')->group(function () {
    Route::get('/', [PostalCodeController::class, 'index'])->name('api.postal-code.index');
    Route::get('/{id}', [PostalCodeController::class, 'show'])->name('api.postal-code.show');
});
