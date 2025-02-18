<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('index', ['items' => $items]);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'condition', 'comments'])->findOrFail($id);

        return view('detail', compact('item'));
    }
}
