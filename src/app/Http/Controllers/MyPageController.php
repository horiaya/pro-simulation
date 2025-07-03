<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Transaction;
use App\Http\Controllers\TransactionController;
use App\Models\Review;

class MyPageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $items = Item::where('user_id', $user->id)->get();

        $purchases = Purchase::with('item')->where('user_id', $user->id)->get();

        //$reviewer = Review::with('user')->where('reviewer', $user->id)->get();

        $transactions = Transaction::with('item')
            ->where('buyer_id', $user->id)
            ->where('status', '!=', 'completed')
            ->get();

        return view('my-page', compact('user', 'items', 'purchases', 'transactions'));
    }
}
