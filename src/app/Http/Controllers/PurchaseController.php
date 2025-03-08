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

        $shippingAddress = session()->get('shipping_address', [
            'post_code' => $user->post_code ?? '',
            'address' => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ]);

        $selectedPaymentMethod = Session::get('selected_payment_method_{$itemId}', '');

        $keepPaymentMethod = session()->pull('keep_payment_method', false);

        if (!$keepPaymentMethod) {
            foreach (session()->all() as $key => $value) {
                if (str_starts_with($key, 'selected_payment_method_') && $key !== "selected_payment_method_{$itemId}") {
                    session()->forget($key);
                }
            }
        }

        return view('purchase', compact('item', 'paymentMethods', 'shippingAddress', 'user', 'selectedPaymentMethod'));
    }

    public function updatePaymentMethod(Request $request, $itemId)
    {
        $request->validate([
            'payment' => 'required|exists:payments,id',
        ]);

        session(['selected_payment_method_{$itemId}' => $request->payment]);

        session()->put('keep_payment_method', true);

        return redirect()->route('purchase.show', ['itemId' => $itemId, 'keep' => true]);
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

        session()->put('keep_payment_method', true);

        return redirect()->route('purchase.show', ['itemId' => $itemId])
                        ->with('success', '住所が更新されました。');
    }
}
