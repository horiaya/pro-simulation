@extends('layouts.default')

<style>
    .header__search, .header__nav {
        display: none;
    }
</style>
@section('content')
<div class="auth-content">
    <h1 class="auth__title">会員登録</h1>
    <div class="auth-group">
        <form class="auth-group__form" action="" method="post">
        @csrf
            <div class="auth__item">
                <p class="auth__item-title">ユーザー名</p>
                <input class="auth__item-input" type="text" name="name">
            </div>
                <div class="auth__item">
                <p class="auth__item-title">メールアドレス</p>
            <input class="auth__item-input" type="email" name="email">
            </div>
            <div class="auth__item">
                <p class="auth__item-title">パスワード</p>
                <input class="auth__item-input" type="password" name="password">
            </div>
            <div class="auth__item">
                <p class="auth__item-title">確認用パスワード</p>
                <input class="auth__item-input" type="password" name="password">
            </div>
            <button class="auth__form-btn">登録する</button>
            <div class="login-link">
                <a href="/login">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection