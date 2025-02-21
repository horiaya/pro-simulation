<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
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
                <img class="header__img" src="{{ asset('storage/images/logo.svg') }}" alt="コーチテックのロゴ">
            </div>
            <div class="header__search">
                <input class="header__search-input" type="search" placeholder="なにをお探しですか？">
            </div>
            <div class="header__nav">
                <form class="header-logout__form" action="{{ route('logout') }}" method="post">
                @csrf
                    <button class="header-logout__btn" type="submit">ログアウト</button>
                </form>
                <a class="header__nav-link" href="">マイページ</a>
                <a class="header__nav-link" href="">出品</a>
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