<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ActivationAccountController;
use App\Http\Controllers\ExamPacketController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\User\AdminAppController;
use App\Http\Controllers\WelderHasExamPacketController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

Route::get('/{any}', [PageController::class, 'app'])->where('any', '^(?!auth|laravel-version|email|attempt|print|export|download).*$');
Route::get('/attempt/{any}', [PageController::class, 'attempt'])->where('any', '^(?!activation-account).*$')->name('web.attempt');
Route::get('/auth/{any}', [PageController::class, 'auth'])->where('any', '^(?!activation-account).*$')->name('web.auth');
Route::get('/print/invoice/{externalId}', [PrintController::class, 'invoice'])->name('web.print.invoice');

Route::get('/auth/activation-account', ActivationAccountController::class);

Route::get('/export/participant/{examPacketId}', [ExamPacketController::class, 'exportParticipant']);

Route::prefix('/download')->group(function () {
    Route::get('/certificate/{examPacketId}', [WelderHasExamPacketController::class, 'downloadCertificate'])->name('web.download.certificate');
    Route::get('/chart/skill', [PrintController::class, 'chartSkill'])->name('web.download.skill');
});

Route::get('/email', function () {
    return QrCode::generate('Make me into a QrCode!');
});
