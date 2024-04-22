<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\IntentsController;

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
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::post('/create-intent', [IntentsController::class, 'store'])->name('createIntent');
// Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
// Route::get('/auth/google/callback', [GoogleAuthController::class, 'callbackGoogle']);

require __DIR__.'/../config/auth.php';