<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Payment;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    public function show($itemId)
    {
        $user = auth()->user();

        $item = Item::findOrFail($itemId);

        $paymentMethods = Payment::all();

        $shippingAddress = Session()->get('shipping_address', [
            'post_code' => $user->post_code ?? '',
            'address' => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ]);

        return view('purchase', compact('item', 'paymentMethods', 'shippingAddress', 'user'));
    }

    public function indexAddress($itemId)
    {
        $user = auth()->user();

        $shippingAddress = session('shipping_address', [
            'post_code' => $user->post_code ?? '',
            'address' => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ]);

        return view('auth.address', compact('itemId', 'shippingAddress'));
    }

    public function updateAddress(PurchaseRequest $request, $itemId)
    {
        Session::put('shipping_address', [
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building_name' => $request->building_name ?? '',
        ]);

        return redirect()->route('purchase.show', ['itemId' => $itemId])
                        ->with('success', '住所が更新されました。');
    }
}
