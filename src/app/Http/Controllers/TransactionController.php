<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use App\Models\TransactionMessage;

class TransactionController extends Controller
{
    public function index($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        $transactions = Transaction::where('item_id', $itemId)
                ->with(['buyer',  'item.user'])
                ->firstOrFail();

        $transactionMessages = TransactionMessage::where('transaction_id', $transactions->id)
                ->with('user')
                ->latest()
                ->get();

        return view('transaction', compact('user', 'item', 'transactions', 'transactionMessages'));
    }
}
