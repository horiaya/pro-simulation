@extends('layouts.default')

@section('content')
<div class="item__group">
    @foreach($items as $item)
    <div class="item__list">
        <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
            <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
        </a>
        <p class="item__list-name">{{ $item->item_name }}</p>
    </div>
    @endforeach
</div>
@endsection