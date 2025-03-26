@extends('layouts.default')

@section('content')
<div class="purchase-content">
    <form id="purchase-form" class="purchase-content__form" action="{{ route('purchase.store', ['itemId' => $item->id]) }}" method="POST">
        @csrf
    <div class="purchase-content--left">
        <div class="purchase__item">
            <div class="purchase__item--left">
                <img class="purchase__item-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
            </div>
            <div class="purchase__item--right">
                <h3 class="purchase__item-name">{{ $item->item_name }}</h3>
                <p class="purchase__item-price">
                    <small class="purchase__item-price--small">¥</small>
                    {{ number_format($item->price) }}
                </p>
            </div>
        </div>
        <div class="payment-method">
            <h4 class="payment-method__title">支払い方法</h4>
                @error('payment')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <select class="payment-method__select" name="payment" id="payment-method">
                    <option value="" disabled {{ session("selected_payment_method_{$item->id}", '') == '' ? 'selected' : '' }}>選択してください</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}" {{ (string) old('payment_method', $selectedPaymentMethod) === (string) $method->id ? 'selected' : '' }}>
                            {{ $method->payment }}
                        </option>
                    @endforeach
                </select>
        </div>
        <div class="shipping-address">
            <div class="shipping-address__head">
                <h4 class="shipping__title">配送先</h4>
                <a class="shipping__link" href="{{ route('address.indexAddress', ['itemId' => $item->id]) }}">変更する</a>
            </div>
            @if(session('success'))
                <div class="alert alert-success" style="color:green;">
                    {{ session('success') }}
                </div>
            @endif
            @php
                $shippingAddress = session('shipping_address', [
                    'post_code' => $user->post_code ?? '',
                    'address' => $user->address ?? '',
                    'building_name' => $user->building_name ?? '',
                ]);
            @endphp
            @if($shippingAddress['post_code'] && $shippingAddress['address'])
                <p class="shipping-content shipping__post">〒{{ $shippingAddress['post_code'] }}</p>
                <p class="shipping-content shipping__address">{{ $shippingAddress['address'] }}</p>
                <p class="shipping-content shipping__building">{{ $shippingAddress['building_name'] ?? '' }}</p>
            @else
                <p class="shipping-empty" style="color:red;">住所が設定されていません。</p>
            @endif
        </div>
    </div>
    <div class="purchase-content--right">
        <div class="subtotal">
            <table class="subtotal__list">
                <tr>
                    <th class="subtotal__txt">商品代金</th>
                    <td class="subtotal__display">
                        <small class="purchase__item-price--small">¥</small>
                    {{ number_format($item->price) }}
                    </td>
                </tr>
                <tr>
                    <th class="subtotal__txt">支払い方法</th>
                    <td id="selected-payment" class="subtotal__display">
                        {{ $selectedPaymentMethod ? \App\Models\Payment::find($selectedPaymentMethod)->payment : '未選択' }}
                    </td>
                </tr>
            </table>
        </div>
            <input type="hidden" name="payment_method" id="hidden-payment-method" value="{{ old('payment_method', session("selected_payment_method_{$item->id}")) }}">
            <button id="purchase-btn" class="purchase__form-btn" type="submit" class="btn btn-primary">購入する</button>
    </div>
    </form>
</div>

<script>
    document.getElementById('payment-method').addEventListener('change', function () {
        document.getElementById('hidden-payment-method').value = this.value;
    });

    document.addEventListener('DOMContentLoaded', () => {
        const paymentSelect = document.getElementById('payment-method');
        const displayElement = document.getElementById('selected-payment');

        const selectedOption = paymentSelect.options[paymentSelect.selectedIndex];
        displayElement.textContent = selectedOption.value ? selectedOption.textContent : "未選択";

        paymentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (this.value === "") {
                displayElement.textContent = "未選択";
            } else {
                displayElement.textContent = selectedOption.textContent;
            }
        });
    });
</script>
<script src="https://js.stripe.com/v3/"></script>
@endsection