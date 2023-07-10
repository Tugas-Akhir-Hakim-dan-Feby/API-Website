<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ActivationAccountController;
use App\Http\Controllers\PrintController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{any}', [PageController::class, 'app'])->where('any', '^(?!auth|laravel-version|email|attempt|print).*$');
Route::get('/attempt/{any}', [PageController::class, 'attempt'])->where('any', '^(?!activation-account).*$')->name('web.attempt');
Route::get('/auth/{any}', [PageController::class, 'auth'])->where('any', '^(?!activation-account).*$')->name('web.auth');
Route::get('/print/invoice/{externalId}', PrintController::class)->name('web.print.invoice');

Route::get('/auth/activation-account', ActivationAccountController::class);

Route::get('/email', function () {
    return view('email.work.reject');
});
