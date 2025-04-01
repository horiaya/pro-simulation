@extends('layouts.default')
<style>
    .success {
        text-align: center;
        margin-top: 100px;
    }
    .success a {
        text-decoration: none;
        display: inline-block;
        background-color: gray;
        color: white;
        padding: 3px 10px;
        border: 1px solid black;
        border-radius: 3px;
        margin-top: 20px;
    }
</style>
@section('content')
<div class="success">
    <h1 class="success__heading">ご購入ありがとうございました！</h1>
    <a href="{{ route('index') }}">トップに戻る</a>
</div>
@endsection