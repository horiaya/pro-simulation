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
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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




Route::get('/verify-email', function () {return view('auth.verify-email');})->name('verify-email');
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::find($id);

    if (!$user || !hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403, 'この認証リンクは無効です。');
    }
    if ($user->hasVerifiedEmail()) {
        Auth::login($user);
        return redirect('/profile')->with('message', 'すでにメール認証が完了しています。');
    }

    $user->markEmailAsVerified();

    Auth::login($user);

    return redirect('/profile')->with('message', 'メール認証が完了しました！');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $email = session('unverified_email');

    if (!$email) {
        return back()->with('message', '認証メールを再送信できません。もう一度登録してください。');
    }
    $user = User::where('email', $request->email)->first();

    if (!$user || $user->hasVerifiedEmail()) {
        return back()->with('message', 'このメールアドレスはすでに認証されています。');
    }

    $user->sendEmailVerificationNotification();

    return back()->with('message', '認証リンクを再送信しました！');
})->middleware(['throttle:6,1'])->name('verification.send');

Route::get('/register', [CustomRegisterController::class, 'create'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'store']);
Route::get('/login', [CustomLoginController::class, 'create'])->name('login');
Route::post('/login', [CustomLoginController::class, 'store']);
Route::post('/logout', [CustomLoginController::class, 'logout'])->name('logout');

Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.detail');
Route::get('/comments/:{item}', [CommentController::class, 'index'])->name('comments.index');
Route::post('/upload-temp-image', [SellController::class, 'uploadTempImage'])
        ->name('upload.temp.image')
        ->middleware('api');
Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');
Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

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
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    Route::get('/purchase/{itemId}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{itemId}/update-payment-method', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.updatePaymentMethod');
    Route::get('/purchase/address/{itemId}', [PurchaseController::class, 'indexAddress'])->name('address.indexAddress');
    Route::post('/address/update/{itemId}', [PurchaseController::class, 'updateAddress'])->name('purchase.updateAddress');
    Route::post('/purchase/store/{itemId}', [PurchaseController::class, 'store'])->name('purchase.store');
});
