@extends('layouts.default')

<style>
    .header__search, .header__nav {
        display: none;
    }
    .verify-email {
        width: 100%;
        text-align: center;
        margin-top: 100px;
    }
    .verify-email__txt {
        margin: 0;
        font-weight: bold;
        font-size: 20px;
    }
    .verify-email__btn {
        display: block;
        margin: 0 auto;
        margin-top: 40px;
        font-size: 15px;
        padding: 7px 20px;
        border: none;
        border-radius: 3px;
        color: blue;
        background-color: white;
    }
    .verify-email__link-mail {
        font-size: 17px;
        padding: 10px 30px;
        color: black;
        background-color: gray;
        text-decoration: none;
        border: 1px solid black;
        border-radius: 3px;
    }
</style>
@section('content')
<div class="verify-email">
    @if (session('message'))
        <p style="color: green;">{{ session('message') }}</p>
    @endif
    <p class="verify-email__txt">登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p class="verify-email__txt">メール認証を完了してください。</p>
    @if (session('unverified_email'))
        @php
            $email = session('unverified_email');
            $emailDomain = substr(strrchr($email, "@"), 1);

            $emailServices = [
                'gmail.com' => 'https://mail.google.com/mail/u/0/#inbox',
                'outlook.com' => 'https://outlook.live.com/mail/inbox',
                'hotmail.com' => 'https://outlook.live.com/mail/inbox',
                'yahoo.com' => 'https://mail.yahoo.com/d/folders/1'
            ];

            // 受信トレイURLを判定（デフォルトはGmail）
            $inboxUrl = $emailServices[$emailDomain] ?? 'https://mail.google.com/mail/u/0/#inbox';
        @endphp
        <div class="verify-email__link" style="margin-top:30px;">
            <a href="{{ $inboxUrl }}" target="_blank" class="verify-email__link-mail" style="background-color:#c0c0c0;">
                認証はこちらから
            </a>
        </div>
    @else
        <p>メールアドレスが登録されていません。もう一度登録してください。</p>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('unverified_email') }}">
        <button class="verify-email__btn" type="submit">確認メールを再送信</button>
    </form>
</div>
@endsection