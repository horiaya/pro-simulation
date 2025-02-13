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
    .verify-email p {
        margin: 0;
        font-weight: bold;
        font-size: 20px;
    }
    .verify-email a {
        display: block;
    }
</style>
@section('content')
<div class="verify-email">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>
    <a href="">認証はこちらから</a>
    <a href="">認証メールを再送する</a>
</div>
@endsection