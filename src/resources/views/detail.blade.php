@extends('layouts.default')

@section('content')
<div class="item__detail">
    <div class="item__img--left">
        <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
    </div>
    <div class="item__content--right">
        <div>
            <h1>{{ $item->item_name }}</h1>
            <p>¥{{ number_format($item->price) }}(税込)</p>
            <form action="">
                <button>購入手続きへ</button>
            </form>
        </div>
        <div>
            <h2>商品説明</h2>
        </div>
        <div>
            <h2>商品の情報</h2>
        </div>
    </div>
</div>
@endsection