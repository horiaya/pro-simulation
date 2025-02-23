<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');

        $query = Item::search($keyword)
                    ->get();

        $items = $query;
        $myListItems = Auth::check() ? Auth::user()->my_lists : [];

        $errorMessage = null;
        if ($items->isEmpty()) {
            $errorMessage = '該当する商品が見つかりませんでした。';
        }

        return view('index', compact(
            'items', 'keyword', 'errorMessage','myListItems'));
    }

    public function show($id)
    {
        $item = Item::with(['category', 'condition', 'categories', 'comments', 'user'])->findOrFail($id);

        $myListCount = $item->myLists()->count();

        $commentCount = $item->comments()->count();

        return view('detail', compact('item', 'myListCount', 'commentCount'));
    }
}
