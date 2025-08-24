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
use App\Http\Requests\TransactionMessageRequest;

class TransactionController extends Controller
{
    public function index($itemId)
    {
        $user = Auth::user();
        $item = Item::findOrFail($itemId);

        $transactions = Transaction::where('item_id', $itemId)
            ->where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)
                ->orWhereHas('item', function ($iq) use ($user) {
                    $iq->where('user_id', $user->id);
                });
            })
                ->with(['buyer',  'item.user'])
                ->firstOrFail();

            if ($user->id === $transactions->buyer_id) {
                $transactions->last_read_at_buyer = now();
            } else {
                $transactions->last_read_at_seller = now();
            }
            $transactions->save();

        $whereVisible = function ($q) use ($user) {
            $q->where('status', 'pending')
            ->orWhere(function ($q) use ($user) {
                $q->where('status', 'completed')
                    ->whereDoesntHave('reviews', function ($rq) use ($user) {
                        $rq->where('reviewer_id', $user->id);
                    });
            })
            ->orWhere(function ($q) use ($user) {
                $q->whereHas('item', function ($iq) use ($user) {
                    $iq->where('user_id', $user->id);
                })
                ->whereNotNull('buyer_id')
                ->whereDoesntHave('reviews', function ($rq) use ($user) {
                    $rq->where('reviewer_id', $user->id);
            });
        });
    };

        $otherTransactions = Transaction::with('item')
            ->where(fn($q) => $q->where('buyer_id', $user->id)
                ->orWhereHas('item', fn($iq) => $iq->where('user_id', $user->id)))
            ->where($whereVisible)
            ->where('id', '!=', $transactions->id)
            ->latest()
            ->get();

        $transactionMessages = TransactionMessage::where('transaction_id', $transactions->id)
                ->with('user')
                ->oldest()
                ->get();

        return view('transaction', compact('user', 'item', 'transactions', 'otherTransactions', 'transactionMessages'));
    }

    public function storeMessage(TransactionMessageRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $data = [
            'sender_id' => Auth::id(),
            'message'   => $validated['message'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('transaction_images', 'public');
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

        if ($transaction->status !== 'completed') {
            $transaction->forceFill([
                'status'       => 'completed',
                'completed_at' => now(),
            ])->save();
        }

        /*$transaction->status = 'completed';
        $transaction->completed_at = now();
        $transaction->save();*/

        /*Transaction::where('status', 'completed')
            ->where('completed_at', '<', now()->subDays(7))
            ->each(function ($transaction) {
                foreach ($transaction->messages as $message) {
                    if ($message->image_path) {
                        \Storage::disk('public')->delete($message->image_path);
                    }
                    $message->delete();
                }
        });*/

        $seller = $transaction->item->user;
        Mail::to($seller->email)->send(new TransactionCompletedMail($transaction));

        return redirect()->back();
    }

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

    public function update(TransactionMessageRequest $request, $id)
    {
        $validated = $request->validated();

        $message = TransactionMessage::where('id', $id)
            ->where('sender_id', Auth::id())
            ->firstOrFail();

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
