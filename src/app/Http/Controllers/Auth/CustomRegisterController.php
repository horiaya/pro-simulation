<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;


class CustomRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request, CreateNewUser $creator)
    {
        $user = $creator->create($request->validated());

        //$user->sendEmailVerificationNotification();

        Auth::logout();

        return redirect()->route('verify-email');
    }
}
