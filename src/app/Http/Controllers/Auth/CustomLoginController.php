<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('index');
        }

        return back()->withErrors([
            'login' => 'ログイン情報が登録されていません',
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        //不要な出品商品画像があれば削除
        $imageTemp = session('image_temp');
        if ($imageTemp) {
            $tempPath = "public/temp/{$imageTemp}";
            if (Storage::exists($tempPath)) {
                Storage::delete($tempPath);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
