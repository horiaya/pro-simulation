@extends('layouts.default')

@section('content')
<div class="detail__item">
    <div class="detail__item--left">
        <img class="detail__item-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
    </div>
    <div class="item__content--right">
        <div class="top-item">
            <h1 class="top-item__name">{{ $item->item_name }}</h1>
            <p class="top-item__price"><span class="top-item__price--small">¥</span>{{ number_format($item->price) }}<span class="top__item-price--small">(税込)</span></p>
            <div class="item__count">
                <div class="item__count-list">
                    <i id="star-icon" class="fa-{{ $isInMyList ? 'solid' : 'regular' }} fa-star" style="color: {{ $isInMyList ? '#fcc800' : '#484848' }};" data-item-id="{{ $item->id }}"></i>
                    <small id="mylist-count" class="item__count-small">{{ $myListCount ?? 0 }}</small>
                </div>
                <div class="item__count-list">
                    <i class="fa-regular fa-comment"></i>
                    <small class="item__count-small">{{ $commentCount ?? 0 }}</small>
                </div>
            </div>
            <form class="purchase-procedure__form" action="">
                <button class="purchase-procedure__btn">購入手続きへ</button>
            </form>
        </div>
        <div class="description__item">
            <h2 class="description__item-title">商品説明</h2>
            <p class="description__item-text">{{ $item->description }}</p>
        </div>
        <div class="information-item">
            <h2 class="information__item-title">商品の情報</h2>
                <p class="information__item-tag">カテゴリー
                    @foreach ($item->categories as $category)
                        <span class="information__item-genre">{{ $category->category }}</span>
                    @endforeach
                </p>
                <p class="information__item-tag">商品の状態
                    <span class="information__item-condition">{{ $item->condition->condition }}</span>
                </p>
        </div>
        <div class="item__comment">
            <h2 class="item__comment-heading">コメント({{ $commentCount ?? 0 }})</h2>
            @if ($item->comments->isNotEmpty())
            <div class="item__comment-list">
                @foreach ($item->comments as $comment)
                    <p class="item__comment-profile"><img class="item__comment-profile-icon" id="preview" src="{{ asset('storage/' . auth()->user()->icon_path) }}" alt="プロフィール画像">{{ $comment->user->name }}</p>
                    <p class="item__comment-list-txt">{{ $comment->comment }}</p>
                @endforeach
            </div>
            @endif
            <form class="item__comment-form" action="">
                <h3 class="item__comment-title">商品へのコメント</h3>
                <textarea class="item__comment-txt" name="" id=""></textarea>
                <button class="item__comment-btn">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let starIcon = document.getElementById('star-icon');
        let myListCount = document.getElementById('mylist-count');

        starIcon.addEventListener('click', function () {
            let itemId = this.dataset.itemId;

            fetch("{{ route('mylist.toggle') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ item_id: itemId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    starIcon.classList.remove('fa-regular');
                    starIcon.classList.add('fa-solid');
                    starIcon.style.color = "#fcc800";
                } else {
                    starIcon.classList.remove('fa-solid');
                    starIcon.classList.add('fa-regular');
                    starIcon.style.color = "#484848";
                }

                if (myListCount) {
                    myListCount.textContent = data.count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection