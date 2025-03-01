@extends('layouts.default')

@section('content')
<div class="item__list-tab">
    <button onclick="showTab('recommend')" id="recommendBtn" class="active">おすすめ</button>
    <button onclick="showTab('mylist')" id="mylistBtn">マイリスト</button>
</div>
<div id="recommendTab" class="item-group">
    @if ($errorMessage)
        <p class="item__alert-message">{{ $errorMessage }}</p>
    @elseif ($items->isNotEmpty())
        @foreach($items as $item)
        <div class="item__list">
            <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
                <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
            </a>
            <p class="item__list-name">{{ $item->item_name }}</p>
        </div>
        @endforeach
    @endif
</div>
<div id="mylistTab" class="item-group" style="display: none;">
    @if ($myListItems->isEmpty() && !empty(request('keyword')))
        <p class="item__alert-message">該当する商品が見つかりませんでした。</p>
    @elseif ($myListItems->isEmpty())
        <p class="item__alert-message">マイリストに登録された商品はありません。</p>
    @else
        @foreach ($myListItems as $item)
            <div class="item__list">
                <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
                    <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
                </a>
                <p class="item__list-name">{{ $item->item_name }}</p>
            </div>
        @endforeach
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

        if (tab === 'mylist') {
            history.pushState(null, '', '/?tab=mylist');
        } else {
            history.pushState(null, '', '/');
        }
    }

    window.onload = function() {
        let urlParams = new URLSearchParams(window.location.search);
        let tab = urlParams.get('tab');

        if (tab === 'mylist') {
            showTab('mylist');
        } else {
            showTab('recommend');
        }
    };
</script>
@endsection