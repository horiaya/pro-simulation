<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\MyList;
use App\Models\User;
use App\Http\Controllers\Controller;

class MyListController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $keyword = $request->input('keyword');
        $myListItems = $user->myListItems()
            ->when(!empty($keyword), function ($query) use ($keyword) {
                return $query->where('item_name', 'like', "%{$keyword}%");
            })
            ->with('category')
            ->get();

        $myListErrorMessage = null;
        if ($myListItems->isEmpty() && !empty($keyword)) {
            $myListErrorMessage = '該当する商品が見つかりませんでした。';
        }

        $items = [];

        return view("index", compact('myListItems', 'items' 'myListErrorMessage '));
    }

    public function toggle(Request $request)
    {
        $user = Auth::user();
        $itemId = $request->input('item_id');

        $existing = MyList::where('user_id', $user->id)
                        ->where('item_id', $itemId)
                        ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            MyList::create([
                'user_id' => $user->id,
                'item_id' => $itemId
            ]);
            $status = 'added';
        }

        $myListCount = MyList::where('item_id', $itemId)->count();

        return response()->json([
            'status' => $status,
            'count' => $myListCount
        ]);
    }
}
