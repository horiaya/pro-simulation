@extends('layouts.default')

@section('content')
@if (!empty($errorMessage))
    <div class="alert alert-search" style="text-align:center;">
        {{ $errorMessage }}
    </div>
@endif
<div class="item__list-tab">
    <button onclick="showTab('recommend')" id="recommendBtn" class="active">おすすめ</button>
    <button onclick="showTab('mylist')" id="mylistBtn">マイリスト</button>
</div>
<div id="recommendTab" class="item-group">
    @foreach($items as $item)
    <div class="item__list">
        <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
            <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
        </a>
        <p class="item__list-name">{{ $item->item_name }}</p>
    </div>
    @endforeach
</div>
<div id="mylistTab" class="item-group" style="display: none;">
    @if (!empty($myListItems) && count($myListItems) > 0)
        @foreach ($myListItems as $item)
            <div class="item__list">
                <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
                    <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
                </a>
                <p class="item__list-name">{{ $item->item_name }}</p>
            </div>
        @endforeach
    @else
        <p class="item__alert-message">マイリストに登録された商品はありません。</p>
    @endif
</div>

<script>
    function showTab(tab) {
        document.getElementById('recommendTab').style.display = 'none';
        document.getElementById('mylistTab').style.display = 'none';

        document.getElementById(tab + 'Tab').style.display = 'block';

        document.getElementById('recommendBtn').classList.remove('active');
        document.getElementById('mylistBtn').classList.remove('active');
        document.getElementById(tab + 'Btn').classList.add('active');
    }
</script>
@endsection