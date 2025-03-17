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
use Illuminate\Support\Facades\Log;


class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        return view('sell', compact('categories' ,'conditions'));
    }

    public function uploadTempImage(Request $request)
    {
        if ($request->hasFile('item_image')) {
            $file = $request->file('item_image');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('public/temp', $filename);

        if ($request->has('old_image_temp')) {
            $oldTempPath = "public/temp/" . $request->input('old_image_temp');
            if (Storage::exists($oldTempPath)) {
                Storage::delete($oldTempPath);
            }
        }

            session(['image_temp' => $filename]);

            return response()->json(['filename' => $filename]);
        }

        return response()->json(['message' => '画像のアップロードに失敗しました'], 400);
    }

    public function store(ExhibitionRequest $request)
    {
        $user = Auth::user();
        $imageTemp = $request->input('image_temp');

        if ($imageTemp) {
            $tempPath = "public/temp/{$imageTemp}";
            $newPath = "public/item_image/{$imageTemp}";

            if (Storage::exists($tempPath)) {
                Storage::move($tempPath, $newPath);
            }

            $imagePath = $imageTemp;
        } else {
            $imagePath = null;
        }

        $item = new Item();
        $item->user_id = $user->id;
        $item->item_name = $request->input('item_name');
        $item->item_brand = $request->input('item_brand');
        $item->description = $request->input('description');
        $item->condition_id = $request->input('condition');
        $item->price = $request->input('price');
        $item->item_image = $imagePath;
        $item->save();

        if ($request->has('category') && is_array($request->input('category'))) {
            $item->categories()->sync($request->input('category'));
        }

        session()->forget('image_temp');

        return redirect(route('mypage.index'))->with('message', '商品の出品が完了しました！');
    }
}
