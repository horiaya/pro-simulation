<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function index($itemId)
    {
        $comments = Comment::where('item_id', $itemId)
            ->with('sender:id, name, icon_path')
            ->latest()
            ->get();

        return response()->json([
            'comments' => $comments,
            'comment_count' => $comments->count(),
        ]);
    }

    public function store(CommentRequest $request)
    {
        $user = Auth::user();

        $comment = Comment::create([
            'sender_id' => $user->id,
            'item_id' => $request->input('item_id'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'コメントが投稿されました');
    }
}
