<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $items = Item::where('user_id', $user->id)->get();

        $purchases = Purchase::where('user_id', $user->id)->get();

        return view('my-page', compact('user', 'items', 'purchases'));
    }
}
