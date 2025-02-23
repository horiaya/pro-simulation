<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\MyList;
use App\Models\User;



class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Item::search($keyword)
                    ->get();

        $items = $query;

        $errorMessage = null;
        if ($items->isEmpty()) {
            $errorMessage = '該当する商品が見つかりませんでした。';
        }

        return view('index', compact(
            'items', 'keyword', 'errorMessage'));
    }

    public function show($id)
    {
        $item = Item::with(['category', 'condition', 'categories', 'comments', 'user'])->findOrFail($id);
        $user = Auth::user();

        $myListCount = MyList::where('item_id', $id)->count();
        $isInMyList = $user ? MyList::where('user_id', $user->id)->where('item_id', $id)->exists() : false;

        $commentCount = $item->comments()->count();

        return view('detail', compact('item', 'commentCount', 'myListCount', 'isInMyList'));
    }
}
