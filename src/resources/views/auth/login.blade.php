@extends('layouts.default')
<style>
    .header__search, .header__nav {
        display: none;
    }
</style>
@section('content')
<div class="auth-content">
    <h1 class="auth__title">ログイン</h1>
    <div class="auth-group">
        <form class="auth__form" action="" method="post">
        @csrf
            <div class="auth__item">
                <p class="auth__item-title">メールアドレス</p>
                <input class="auth__item-input" type="email" name="email">
            </div>
            <div class="auth__item">
                <p class="auth__item-title">パスワード</p>
                <input class="auth__item-input" type="password" name="password">
            </div>
            <button class="auth__form-btn">ログインする</button>
            <div class="register-link">
                <a href="/register">会員登録はこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection