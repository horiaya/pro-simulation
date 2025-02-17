@extends('layouts.default')
<style>
    .header__search, .header__nav {
        display: none;
    }
</style>
@section('content')
<div class="auth-content">
    <h1 class="auth__title">ログイン</h1>
    @error('login')
        <p class="error-message" style="text-align:center;">{{ $message }}</p>
    @enderror
    <div class="auth-group">
        <form class="auth__form" action="/login" method="post">
        @csrf
            <div class="auth__item">
                <p class="auth__item-title">メールアドレス</p>
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="auth__item-input" type="email" name="email" value="{{ old('email') }}">
            </div>
            <div class="auth__item">
                <p class="auth__item-title">パスワード</p>
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
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