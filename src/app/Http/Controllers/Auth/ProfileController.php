<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        return view('Auth.profile', compact('user'),[
            'formAction' => route('profile.store'),
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('Auth.profile', compact('user'),[
            'formAction' => route('profile.update'),
        ]);
    }

    public function store(ProfileRequest $request)
    {
        $this->updateProfile($request);
        return redirect()->route('index')->with('success', 'プロフィールを設定しました！');
    }

    public function update(ProfileRequest $request)
    {
        $this->updateProfile($request);
        return redirect()->route('mypage.index')->with('success', 'プロフィールを更新しました！');
    }

    private function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->post_code = $request->input('post_code');
        $user->address = $request->input('address');
        $user->building_name = $request->input('building_name');

        if ($request->hasFile('icon_path')) {
            $path = $request->file('icon_path')->store('icons', 'public');
            $user->icon_path = $path;
        }

        $user->save();
    }
}
