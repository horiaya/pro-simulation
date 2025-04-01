<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\MyList;
use App\Models\User;
use App\Models\Purchase;



class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $soldItemIds = Purchase::pluck('item_id')->toArray();

        if (!Auth::check()) {
            $items = Item::search($keyword)->get();
            $myListItems = collect();
        } else {
            $items = Item::where('user_id', '!=', Auth::id())
                        ->search($keyword)
                        ->get();

            $myListItems = Auth::user()->myListItems()
                        ->where(function ($query) use ($keyword) {
                            if (!empty($keyword)) {
                                $query->where('item_name', 'like', "%{$keyword}%");
                            }
                        })
                        ->get();
        }

        $errorMessage = null;
        if ($items->isEmpty()) {
            $errorMessage = '該当する商品が見つかりませんでした。';
        }

        return view('index', compact(
            'items', 'keyword', 'errorMessage', 'myListItems', 'soldItemIds'));
    }

    public function show($id)
    {
        $item = Item::with(['category', 'condition', 'comments', 'user'])->findOrFail($id);
        $user = Auth::user();
        $isSold = Purchase::where('item_id', $id)->exists();

        foreach (session()->all() as $key => $value) {
            if (str_starts_with($key, 'selected_payment_method_')) {
                session()->forget($key);
            }
        }

        $myListCount = MyList::where('item_id', $id)->count();
        $isInMyList = $user ? MyList::where('user_id', $user->id)->where('item_id', $id)->exists() : false;

        $comments = $item->comments;
        $commentCount = $item->comments()->count();

        return view('detail', compact('item', 'myListCount', 'isInMyList', 'comments', 'commentCount', 'isSold'));
    }
}
