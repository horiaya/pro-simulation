@extends('layouts.default')

@section('content')
<div class="purchase-content">
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
            <form action="{{ route('purchase.updatePaymentMethod', ['itemId' => $item->id, 'keep' => true]) }}" method="POST">
            @csrf
                <select class="payment-method__select" name="payment" id="payment-method">
                    <option value="" disabled {{ empty($selectedPaymentMethod) ? 'selected' : '' }}>選択してください</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->id }}" {{ (string)$selectedPaymentMethod === (string)$method->id ? 'selected' : '' }}>{{ $method->payment }}</option>
                    @endforeach
                </select>
            </form>
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
                <p class="shipping-empty">住所が設定されていません。</p>
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
        <form class="purchase__form" action="" method="">
            @csrf
            <button class="purchase__form-btn" type="submit" class="btn btn-primary">購入する</button>
        </form>
    </div>
</div>
<script>
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

            this.form.submit();
        });
    });
</script>
@endsection