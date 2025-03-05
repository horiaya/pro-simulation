@extends('layouts.default')

@section('content')
<div class="shipping-address__update">
    <div class="shipping-address__heading">
        <h1 class="shipping-address__heading-title">住所の変更</h1>
    </div>
    <div class="shipping-address__list">
        <form class="shipping-address__form" action="{{ route('purchase.updateAddress', ['itemId' => $itemId]) }}" method="post">
        @csrf
            <div class="shipping-address__item">
                <h3 class="shipping-address__title">郵便番号</h3>
                @error('post_code')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="shipping-address__input" type="text" name="post_code" value="{{ old('post_code', $shippingAddress['post_code'] ?? '') }}">
            </div>
            <div class="shipping-address__item">
                <h3 class="shipping-address__title">住所</h3>
                @error('address')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="shipping-address__input" type="text" name="address" value="{{ old('address', $shippingAddress['address'] ?? '') }}">
            </div>
            <div class="shipping-address__item">
                <h3 class="shipping-address__title">建物名</h3>
                <input class="shipping-address__input" type="text" name="building_name" value="{{ old('building_name', $shippingAddress['building_name'] ?? '') }}">
            </div>
            <div class="shipping-address__update">
                <button type="submit" class="shipping-address__update-btn">更新する</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.querySelector('.shipping-address__form').addEventListener('submit', function() {
        console.log('フォームが送信されました');
    });
</script>
@endsection