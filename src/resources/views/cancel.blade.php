@extends('layouts.default')
<style>
    .cancel {
        text-align: center;
    }
    .cancel a {
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
<div class="cancel">
    <h1 class="cancel__heading">お支払いがキャンセルされました</h1>
    <p class="cancel__txt">もう一度お試しください</p>
    <a href="{{ route('index') }}">トップに戻る</a>
</div>
@endsection