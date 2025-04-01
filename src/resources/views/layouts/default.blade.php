<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/address.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>coachtech</title>
</head>
<style>
    header {
        background-color:black;
        height: 55px;
    }
    .content {
        padding: 25px;
    }
    .header-content {
        display: flex;
        justify-content: space-between;
        margin: 0 10px;
        height: 55px;
    }
    .header__logo , .header__search {
        margin: auto 0;
    }
    .header__search-form {
        line-height: 55px;
        margin: 0;
    }
    .header__search-input {
        width: 350px;
        padding: 10px 30px;
        font-size: 15px;
    }
    .header__nav {
        line-height: 55px;
    }
    .header-logout__form {
        display: inline-block;
    }
    .header-logout__btn {
        border:none;
        background-color: black;
        font-size: 17px;
    }
    .header__nav-link, .header-logout__btn {
        color: white;
        text-decoration: none;
        margin: 0 10px;
    }
    .header__nav-link:last-child {
        background-color: white;
        color: black;
        border-radius: 3px;
        padding: 10px 15px;
    }
    @media screen and (max-width: 850px){
        .header__img {
            width: 200px;
            height: 25px;
        }
        .header__search-input {
            width: 200px;
            font-size: 10px;
        }
        .header__nav-link {
            margin: 0 5px;
        }
    }
</style>
<body>
    <header>
        <div class="header-content">
            <div class="header__logo">
                <a href="{{ route('index') }}">
                    <img class="header__img" src="{{ asset('storage/images/logo.svg') }}" alt="コーチテックのロゴ">
                </a>
            </div>
            <div class="header__search">
                <form id="searchForm" class="header__search-form" action="{{ route('index') }}" method="get">
                    <input class="header__search-input search__name" type="text" name="keyword" placeholder="なにをお探しですか？" onkeypress="submitOnEnter(event)" value="{{ request('keyword') }}" />
                </form>
            </div>
            <div class="header__nav">
                <form class="header-logout__form" action="{{ route('logout') }}" method="post">
                @csrf
                @auth
                    <button class="header-logout__btn" type="submit">ログアウト</button>
                @else
                    <a class="header-logout__btn" href="{{ route('login') }}">ログイン</a>
                @endauth
                </form>
                <a class="header__nav-link" href="{{ route('mypage.index') }}">マイページ</a>
                <a class="header__nav-link" href="{{ route('sell.create') }}">出品</a>
            </div>
        </div>
    </header>
    <main>
        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>
<script>
    function submitOnEnter(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('searchForm').submit();
        }
    }
</script>