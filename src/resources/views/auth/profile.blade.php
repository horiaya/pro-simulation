@extends('layouts.default')

@section('content')
<div class="profile-content">
    <h1 class="profile__title">プロフィール設定</h1>
    <div class="profile-group">
        <form class="profile-group__form" action="">
            <div class="profile__item">
                <p class="profile__item-title">ユーザー名</p>
                <input class="profile__item-input" type="text">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">郵便番号</p>
                <input class="profile__item-input" type="text">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">住所</p>
                <input class="profile__item-input" type="text">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">建物名</p>
                <input class="profile__item-input" type="text">
            </div>
            <button class="profile__form-btn">更新する</button>
        </form>
    </div>
</div>
@endsection