<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function index(Item $item)
    {
        $comments = Comment::where('item_id', $item->id)->with('sender')->latest()->get();

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

        return response()->json([
            'comment' => [
                'comment' => $comment->comment,
                'name' => $user->name,
                'icon_path' => $user->icon_path ?? 'default.png',
                'created_at' => $comment->created_at->format('Y-m-d H:i'),
            ],
            'comment_count' => Comment::where('item_id', $request->input('item_id'))->count(),
        ]);
    }
}
