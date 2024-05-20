<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\IntentsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GoogleAuthController;

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
 

Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google-auth');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [IntentsController::class, 'index'])->name('dashboard');
Route::get('/train', function () {
    return view('train');
})->name('train');
Route::get('/archivePage', [ArchiveController::class, 'index'])->name('archivePage');
Route::get('/reports', [FeedbackController::class, 'index'])->name('reports');
Route::post('/create-intent', [IntentsController::class, 'store'])->name('createIntent');
Route::post('/edit-intent', [IntentsController::class, 'editIntent'])->name('editIntent');
Route::post('/archive-intent', [IntentsController::class, 'archiveIntent'])->name('archiveIntent');
Route::post('/add-back', [ArchiveController::class, 'restoreIntent'])->name('restoreIntent');
Route::post('/del-archive', [ArchiveController::class, 'deleteArchive'])->name('deleteArchive');
Route::get('/chart-data', [FeedbackController::class, 'fetchChartData']);//rating
Route::get('/feedback-data', [FeedbackController::class, 'fetchFeedbackData']);//feedback
Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
Route::put('/feedback/{id}', [FeedbackController::class, 'update'])->name('feedback.update');



require __DIR__.'/../config/auth.php';
