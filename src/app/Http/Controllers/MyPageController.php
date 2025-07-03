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

        $purchases = Purchase::with('item')
            ->where('user_id', $user->id)
            ->get();

        $transactions = Transaction::with(['item', 'messages'])
            ->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('buyer_id', $user->id)
                        ->where('status', '!=', 'completed');
                })
                ->orWhere(function ($q) use ($user) {
                    $q->whereHas('item', function ($itemQ) use ($user) {
                        $itemQ->where('user_id', $user->id);
                    })
                    ->where('status', '!=', 'completed')
                    ->whereHas('messages', function ($msgQ) use ($user) {
                        $msgQ->where('sender_id', '!=', $user->id);
                    });
                });
            })
            ->get();

        foreach ($transactions as $transaction) {
            $isBuyer = $transaction->buyer_id === $user->id;

            $lastRead = $isBuyer ? $transaction->last_read_at_buyer : $transaction->last_read_at_seller;

            $unreadCount = $transaction->messages()
                ->where('sender_id', '!=', $user->id)
                ->when($lastRead, function ($q) use ($lastRead) {
                    $q->where('created_at', '>', $lastRead);
                })
                ->count();

            $transaction->unread_count = $unreadCount;
        }

        $totalUnreadCount = $transactions->sum('unread_count');

        $average = Review::where('reviewee_id', $user->id)->avg('rating');
        $average = $average ? round($average) : null;

        return view('my-page', compact('user', 'items', 'purchases', 'purchases', 'transactions', 'average', 'totalUnreadCount'));
    }
}
