<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories' ,'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        //dd($request->all());
        $user = Auth::user();

        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $imagePath = $request->file('item_image')->store('public/item_image');
            //session(['image_temp' => str_replace('public/', 'storage/', $imagePath)]);
            $filename = basename($imagePath);
        }

        $item = new Item();
        $item->user_id = $user->id;
        $item->item_name = $request->input('item_name');
        $item->item_brand = $request->input('item_brand');
        $item->description = $request->input('description');
        $item->condition_id = $request->input('condition');
        $item->price = $request->input('price');
        $item->item_image = $filename;
        $item->save();

        if ($request->has('category') && is_array($request->input('category'))) {
            $item->categories()->sync($request->input('category'));
        }

        session()->forget('image_temp');

        return redirect(route('mypage.index'))->with('message', '商品の出品が完了しました！');
    }
}
