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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionCompletedMail;

class TransactionController extends Controller
{
    public function index($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        $transactions = Transaction::where('item_id', $itemId)
                ->with(['buyer',  'item.user'])
                ->firstOrFail();

            if ($user->id === $transactions->buyer_id) {
                $transactions->last_read_at_buyer = now();
            } else {
                $transactions->last_read_at_seller = now();
            }
            $transactions->save();

        $otherTransactions = Transaction::with(['item'])
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
            ->where('id', '!=', $transactions->id)
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
            'message' => 'nullable|required_without:image|string|max:400',
            'image'   => 'nullable|image|mimes:jpeg,png'
        ],[
            'message.required_without' => 'メッセージまたは画像を入力してください。',
            'message.string' => 'メッセージは文字列で入力してください。',
            'message.max' => 'メッセージは400文字以内で入力してください。',
            'image.image' => 'アップロードするファイルは画像形式にしてください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像のサイズは5MB以内にしてください。',
        ]);

        $data = [
            'sender_id' => Auth::id(),
            'message'   => $validated['message'] ?? null,
        ];

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

        if (Auth::id() !== $transaction->buyer_id) {
            abort(403);
        }

        $transaction->status = 'completed';
        $transaction->save();

        $seller = $transaction->item->user;
        Mail::to($seller->email)->send(new TransactionCompletedMail($transaction));

        return redirect()->back();
    }

    /*public function show($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }*/

    public function reviewStore(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        $revieweeId = auth()->id() === $transaction->buyer_id
        ? $transaction->item->user->id
        : $transaction->buyer_id;

        $review = new Review();
        $review->transaction_id = $transaction->id;
        $review->reviewer_id = auth()->id();
        $review->reviewee_id = $revieweeId;
        $review->rating = $request->rating;
        $review->save();

        return redirect()->route('index')->with('success', 'レビューを送信しました！');
    }

    public function update(Request $request, $id)
    {
        $message = TransactionMessage::where('id', $id)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'nullable|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png',
        ],[
            'message.required_without' => 'メッセージまたは画像を入力してください。',
            'message.string' => 'メッセージは文字列で入力してください。',
            'message.max' => 'メッセージは400文字以内で入力してください。',
            'image.image' => 'アップロードするファイルは画像形式にしてください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像のサイズは5MB以内にしてください。',
        ]);

        if ($request->hasFile('image')) {
            if ($message->image_path) {
                Storage::delete($message->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('transaction_images', 'public');
        } else {
            $validated['image_path'] = $message->image_path;
        }

        $message->update([
            'message' => $validated['message'],
            'image_path' => $validated['image_path'],
        ]);

        return response()->json(['message' => $message->load('user')]);
    }

    public function destroy($id)
    {
        $message = TransactionMessage::where('id', $id)->where('sender_id', Auth::id())->firstOrFail();

        if ($message->image_path) {
            Storage::disk('public')->delete($message->image_path);
        }

        $message->delete();

        return back()->with('status', 'メッセージを削除しました');
    }
}
