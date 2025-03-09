<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\CustomRegisterController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\MyListController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;

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




Route::get('/verify-email', function () {
    return view('auth.verify-email');
})->name('verify-email');
Route::get('/register', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'store']);
Route::get('/login', [CustomLoginController::class, 'create'])->name('login');
Route::post('/login', [CustomLoginController::class, 'store']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.detail');
Route::get('/comments/:{item}', [CommentController::class, 'index'])->name('comments.index');

/*Route::middleware(['auth', 'profile.completed'])->group(function () {
    Route::get('/', [ItemController::class, 'index'])->name('index');
});*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'create'])->name('profile.create');
    Route::put('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/mylist', [MyListController::class, 'index'])->name('mylist.index');
    Route::post('/mylist/toggle', [MyListController::class, 'toggle'])->name('mylist.toggle');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/mypage', [MyPageController::class, 'index'])->name('mypage.index');
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::get('/purchase/{itemId}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{itemId}/update-payment-method', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.updatePaymentMethod');
    Route::get('/purchase/address/{itemId}', [PurchaseController::class, 'indexAddress'])->name('address.indexAddress');
    Route::post('/address/update/{itemId}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');
});
