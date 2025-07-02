@extends('layouts.default')

@section('hide-header-nav')
<div></div>
@endsection

@section('main-class', 'no-padding')

@section('content')
<div class="transaction">
    @php
        $isBuyer = $user->id === $transactions->buyer_id;

        $partner = $isBuyer ? $transactions->item->user : $transactions->buyer;
    @endphp
    <div class="transaction__group--left">
        <p class="transaction__side-title">その他の取引</p>
    </div>
    <div class="transaction__group--right">
        <div class="transaction__head">
            <div class="transaction__head-heading">
            @if($partner && $partner->icon_path)
                <img class="transaction__head-icon" src="{{ Storage::url($transaction->buyer->icon_path) }}" alt="プロフィール画像">
            @else
                <div class="transaction__head-placeholder"></div>
            @endif
                <h1 class="transaction__head-title">{{ $partner->name }}さんとの取引画面</h1>
            </div>
            <div class="transaction__head-side">
                <button class="transaction__head-btn">取引を完了する</button>
            </div>
        </div>
        <div class="transaction__item">
            <div class="transaction__item-img">
                <img class="transaction__item-img--left" src="{{ Storage::url('item_image/' . $transactions->item->item_image) }}" alt="商品画像">
            </div>
            <div class="transaction-group__item">
                <div class="transaction__item--large">
                    <h2 class="transaction__item-name">{{$transactions->item->item_name}}</h2>
                </div>
                <div class="transaction__item--small">
                    <p class="transaction__item-price">¥{{ number_format($transactions->item->price) }}</p>
                </div>
            </div>
        </div>
        <div class="message">
        @foreach ($transactionMessages as $transactionMessage)
            @php
                $isMyMessage = $transactionMessage->sender_id === Auth::id();
                $user = $transactionMessage->user;
            @endphp
            <div class="message-group {{ $isMyMessage ? 'message__myself--right' : 'message__partner--left' }}">
                <div class="message__user">
                    @if ($user && $user->icon_path)
                        <img class="message__user-img" src="{{ Storage::url($user->icon_path) }}" alt="プロフィール画像">
                    @else
                        <div class="message__user-placeholder"></div>
                    @endif
                    <p class="message__user-name">{{ $user ? $user->name : '不明なユーザー' }}</p>
                </div>
                <div class="message-content">{{ $transactionMessage->message }}</div>
                    @if ($isMyMessage)
                        <div class="message__edit">
                            <a class="message__edit-btn" href="#">編集</a>
                            <a class="message__edit-btn message__delete" href="#">削除</a>
                        </div>
                    @endif
            </div>
        @endforeach
        </div>
    </div>
</div>
@endsection