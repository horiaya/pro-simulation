<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\TransactionMessage;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        $transactions = Transaction::where('item_id', $itemId)
                ->with(['buyer',  'item.user'])
                ->firstOrFail();

        $otherTransactions = Transaction::where('buyer_id', Auth::id())
                ->where('id', '!=', $transactions->id)
                ->with(['item'])
                ->get();

        $transactionMessages = TransactionMessage::where('transaction_id', $transactions->id)
                ->with('user')
                ->oldest()
                ->get();

        return view('transaction', compact('user', 'item', 'transactions', 'otherTransactions', 'transactionMessages'));
    }

    public function storeMessage(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'message' => 'nullable|required_without:image|string|max:500',
            'image'   => 'nullable|image|mimes:jpeg,png'
        ],[
            'message.required_without' => 'メッセージまたは画像を入力してください。',
            'message.string' => 'メッセージは文字列で入力してください。',
            'message.max' => 'メッセージは500文字以内で入力してください。',
            'image.image' => 'アップロードするファイルは画像形式にしてください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像のサイズは5MB以内にしてください。',
        ]);

        $data = [
            'sender_id' => Auth::id(),
            'message'   => $validated['message'] ?? null,
        ];

        \Log::info('Files:', $request->allFiles());
\Log::info('Has file: ' . ($request->hasFile('image') ? 'yes' : 'no'));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('transaction_image', 'public');
            $data['image_path'] = $path;
        }

        $message = $transaction->messages()->create($data);
        $message->load('user');

        return response()->json([
            'message' => $message
        ]);
    }

    public function complete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->status = 'completed';
        $transaction->save();

        return redirect()->route('transactions.show', $transaction->id);
    }

    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function reviewStore(Request $request)
    {
        $review = new Review();
        $review->reviewer_id = auth()->id();
        $review->reviewee_id = $request->reviewee_id;
        $review->rating = $request->rating;
        $review->save();

        $average = Review::where('reviewee_id', $review->reviewee_id)->avg('rating');

        $user = User::find($review->reviewee_id);
        $user->rating = $average;
        $user->save();

        return redirect()->route('mypage')->with('success', 'レビューを送信しました！');
    }
}
