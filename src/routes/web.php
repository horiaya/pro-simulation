<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\ProfileController;

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



Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verify-email');
Route::get('/register', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'store']);
Route::get('/login', [CustomLoginController::class, 'create'])->name('login');
Route::post('/login', [CustomLoginController::class, 'store']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
    Route::get('/detail/{id}', [ItemController::class, 'show'])->name('item.detail');
});
