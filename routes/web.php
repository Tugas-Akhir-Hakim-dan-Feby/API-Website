<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ActivationAccountController;

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

Route::get('/{any}', [PageController::class, 'app'])->where('any', '^(?!auth|laravel-version|email).*$');
Route::get('/auth/{any}', [PageController::class, 'auth'])->where('any', '^(?!activation-account).*$');

Route::get('/auth/activation-account', ActivationAccountController::class);
