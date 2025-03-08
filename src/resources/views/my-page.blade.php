@extends('layouts.default')

@section('content')
    <div class="mypage-content">
        <div class="mypage__head">
            @if($user->icon_path)
                <img class="mypage__icon" src="{{ asset('storage/' . $user->icon_path) }}" alt="プロフィール画像">
            @else
                <div class="mypage__placeholder"></div>
            @endif
            <h2 class="mypage__user-name">{{ $user->name }}</h2>
            <div class="mypage__profile">
                <a class="mypage__profile-link" href="{{ route('profile.edit') }}">プロフィールを編集</a>
            </div>
        </div>
        <div class="item__list-tab">
            <button onclick="showTab('sell')" id="sellBtn" class="active">出品した商品</button>
            <button onclick="showTab('purchase')" id="purchaseBtn">購入した商品</button>
        </div>
        <div id="sellTab" class="sell-item">
        @if($items->isEmpty())
            <p class="sell-empty-message">出品した商品はありません。</p>
        @else
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
        <div id="purchaseTab" class="purchase-item">
        @if($purchases->isEmpty())
            <p class="purchase-empty-message">購入した商品はありません。</p>
        @else
            @foreach($purchases as $purchase)
                <div class="item__list">
                    <a class="item__list-link" href="{{ route('item.detail', ['id' => $item->id]) }}">
                        <img class="item__list-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
                    </a>
                    <p class="item__list-name">{{ $item->item_name }}</p>
                </div>
            @endforeach
        @endif
        </div>
    </div>

    <script>
        window.onload = function() {
            showTab('sell');
        };

        function showTab(tab) {
        document.getElementById('sellTab').style.display = 'none';
        document.getElementById('purchaseTab').style.display = 'none';

        document.getElementById(tab + 'Tab').style.display = 'block';

        document.getElementById('sellBtn').classList.remove('active');
        document.getElementById('purchaseBtn').classList.remove('active');
        document.getElementById(tab + 'Btn').classList.add('active');
    }
    </script>
@endsection