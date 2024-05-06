<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\IntentsController;
use App\Http\Controllers\DashboardController;
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
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::post('/register', [UsersController::class, 'store'])->name('createUser');
Route::get('/dashboard', [IntentsController::class, 'index'])->name('dashboard');
Route::get('/train', function () {
    return view('train');
})->name('train');
Route::post('/create-intent', [IntentsController::class, 'store'])->name('createIntent');
Route::post('/edit-intent', [IntentsController::class, 'editIntent'])->name('editIntent');


require __DIR__.'/../config/auth.php';
