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
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Log;
use App\Models\Transaction;

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

        $keep = session()->get('keep_payment_method', false);

        $selectedPaymentMethod = $keep
            ? session("selected_payment_method_{$itemId}", '')
            : old('payment', '');

        session()->forget('keep_payment_method');

        return view('purchase', compact('item', 'paymentMethods', 'shippingAddress', 'user', 'selectedPaymentMethod'));
    }

    public function updatePaymentMethod(Request $request, $itemId)
    {
        session()->put("selected_payment_method_{$itemId}", $request->input('payment'));
        session()->put('keep_payment_method', true);

        return response()->json(['status' => 'OK']);
    }

    public function indexAddress($itemId)
    {
        $user = auth()->user();

        $shippingAddress = session('shipping_address', [
            'post_code' => $user->post_code ?? '',
            'address' => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ]);

        $selectedPaymentMethod = session("selected_payment_method_{$itemId}", '');

        return view('auth.address', compact('itemId', 'shippingAddress', 'selectedPaymentMethod'));
    }

    public function updateAddress(Request $request, $itemId)
    {
        $request->validate([
            'post_code' => ['required', 'string', 'regex:/^\d{3}-\d{4}$/'],
            'address' => ['required', 'string', 'max:100'],
            'building_name' => ['nullable', 'string', 'max:100'],
        ], [
            'post_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'address.required' => '住所を入力してください',
        ]);

        Session::put('shipping_address', [
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building_name' => $request->building_name ?? '',
        ]);

        session()->put('keep_payment_method', true);
        Session::save();

        return redirect()->route('purchase.show', ['itemId' => $itemId])
                        ->with('success', '住所が更新されました。');
    }

    private function getShippingAddress($user)
    {
        return session('shipping_address', [
            'post_code' => $user->post_code ?? '',
            'address' => $user->address ?? '',
            'building_name' => $user->building_name ?? '',
        ]);
    }

    public function store(PurchaseRequest $request, $itemId)
    {
        $user = auth()->user();
        $item = Item::findOrFail($itemId);
        Stripe::setApiKey(config('services.stripe.secret'));

        session(["selected_payment_method_{$itemId}" => $request->input('payment')]);

        $paymentMethodId = $request->input('payment');
        $paymentMethod = Payment::find($paymentMethodId);
        $paymentType = $paymentMethod->slug ?? 'card';
        $stripePaymentType = $paymentType === 'konbini' ? 'konbini' : 'card';

        $validated = $request->validated();

        $shippingAddress = $this->getShippingAddress($user);

        $session = StripeSession::create([
            'payment_method_types' => [$stripePaymentType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->item_name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('purchase.cancel'),
            'metadata' => [
                'user_id' => $user->id,
                'item_id' => $itemId,
                'payment_method' => $paymentMethodId,
                'post_code' => $shippingAddress['post_code'],
                'address' => $shippingAddress['address'],
                'building_name' => $shippingAddress['building_name'] ?? '',
            ]
        ]);

        return redirect($session->url);
    }

    public function handleWebhook(Request $request)
    {
        //\Log::info('✅ WebhookがLaravelに到達しました');

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if (app()->environment('testing')) {
            $event = json_decode($payload);
        } else {
            try {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            } catch (\Exception $e) {
                /*Log::error('Webhook署名検証エラー', ['error' => $e->getMessage(),
            'sigHeader' => $sigHeader,
            'payload' => $payload,]);*/
                return response('Invalid payload or signature', 400);
            }
        }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        //$metadata = $session->metadata ? $session->metadata->toArray() : [];

        if (app()->environment('testing')) {
            $metadata = (array) ($session->metadata ?? []);
        } else {
            $metadata = $session->metadata ? $session->metadata->toArray() : [];
        }
        /*if (!isset($metadata['user_id'], $metadata['item_id'])) {
            return response('Missing required metadata', 400);
        }*/
        if (
            empty($metadata['user_id']) ||
            empty($metadata['item_id']) ||
            empty($metadata['payment_method']) ||
            empty($metadata['post_code']) ||
            empty($metadata['address'])
        ) {
            \Log::error('Webhookメタデータが不足しています', ['metadata' => $metadata]);
            return response('Invalid metadata', 400);
        }

        Purchase::create([
            'user_id' => (int) $metadata['user_id'],
            'item_id' => (int) $metadata['item_id'],
            'payment_id' => (int) $metadata['payment_method'],
            'post_code' => $metadata['post_code'],
            'address' => $metadata['address'],
            'building_name' => $metadata['building_name'] ?? '',
        ]);

        Transaction::create([
            'buyer_id' => (int) $metadata['user_id'],
            'item_id' => (int) $metadata['item_id'],
            'status' => 'pending',
        ]);
    }

        return response('Webhook handled', 200);
    }

    public function success()
    {
        return view('success');
    }

    public function cancel()
    {
        return view('cancel');
    }
}

