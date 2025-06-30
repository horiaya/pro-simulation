@extends('layouts.default')

@section('hide-header-nav')
<div></div>
@endsection

@section('main-class', 'no-padding')

@section('content')
<div class="transaction">
    <div class="transaction__group--left">
        <p class="transaction__side-title">その他の取引</p>
    </div>
    <div class="transaction__group--right">
        <div class="transaction__head">
            <div class="transaction__head-heading">
            @if($user->icon_path)
                <img class="transaction__head-icon" src="{{ Storage::url($user->icon_path) }}" alt="プロフィール画像">
            @else
                <div class="transaction__head-placeholder"></div>
            @endif
                <h1 class="transaction__head-title">さんとの取引画面</h1>
            </div>
            <div class="transaction__head-side">
                <button class="transaction__head-btn">取引を完了する</button>
            </div>
        </div>
        <div class="transaction__item">
            <div class="transaction__item-img">
                <img src="" alt="商品画像">
            </div>
            <div class="transaction-group__item">
                <div class="transaction__item--large">
                    <h2 class="transaction__item-name">あああ</h2>
                </div>
                <div class="transaction__item--small">
                    <small class="transaction__item-price">あああ</small>
                </div>
            </div>
        </div>
        <div class="message">
            <div class="message-group message__myself--right">
                <div class="message__user">
                    <img class="message__user-img" src="" alt="プロフィール画像">
                    <p class="message__user-name">ユーザー名</p>
                </div>
                <div class="message-content">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                </div>
                <div class="message__edit">
                    <a class="message__edit-btn" href="#">編集</a> <a class="message__edit-btn message__delete" href="#">削除</a>
                </div>
            </div>
            <div class="message-group message__partner--left">
                <div class="message__user">
                    <img class="message__user-img" src="" alt="プロフィール画像">
                    <p class="message__user-name">ユーザー名</p>
                </div>
                <div class="message-content">aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
                </div>
            </div>
        </div>
    </div>
</div>
@endsection