<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;

class TransactionController extends Controller
{
    // public function index($itemId)
    public function index()
    {
        $user = Auth::user();
        //$item = Item::with(['transaction', 'user'])->findOrFail($itemId);

        $transaction = transaction::all();
        $transactionMessage = transaction::all();

        return view('transaction', compact('user', 'transaction', 'transactionMessage'));
    }
}
