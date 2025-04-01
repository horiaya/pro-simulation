@extends('layouts.default')

@section('content')
<div class="detail__item">
    <div class="detail__item--left">
        <img class="detail__item-img" src="{{ asset('storage/item_image/' . $item->item_image) }}" alt="商品画像">
    </div>
    <div class="item__content--right">
        <div class="top-item">
            <h1 class="top-item__name">{{ $item->item_name }}</h1>
            <p class="top-item__brand">{{ $item->item_brand }}</p>
            <p class="top-item__price">
                <span class="top-item__price--small">¥</span>
                {{ number_format($item->price) }}
                <span class="top__item-price--small">(税込)</span>
            </p>
            <div class="item__count">
                <div class="item__count-list">
                    <i id="star-icon" class="fa-{{ $isInMyList ? 'solid' : 'regular' }} fa-star" style="color: {{ $isInMyList ? '#fcc800' : '#484848' }};" data-item-id="{{ $item->id }}"></i>
                    <small id="mylist-count" class="item__count-small">{{ $myListCount ?? 0 }}</small>
                </div>
                <div class="item__count-list">
                    <i class="fa-regular fa-comment"></i>
                    <small id="comment-count-top" class="item__count-small">{{ $commentCount ?? 0 }}</small>
                </div>
            </div>
            @if (!$isSold)
            <form class="purchase-procedure__form" action="{{ route('purchase.show', ['itemId' => $item->id]) }}">
                <button class="purchase-procedure__btn">購入手続きへ</button>
            </form>
            @else
                <p class="purchase-procedure__sold" style="color:red;">この商品はすでに購入されています</p>
            @endif
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
            <h2 class="item__comment-heading">コメント(
                <span id="comment-count-bottom">{{ $commentCount ?? 0 }}</span>)
            </h2>
                @if (session('success'))
                    <p class="success-message" style="color: green;">{{ session('success') }}</p>
                @endif
            <div id="comment-list" class="item__comment-list">
                @foreach ($comments ?? [] as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        @if($comment->sender->icon_path)
                            <img class="comment-icon" src="{{ asset('storage/' . $comment->sender->icon_path) }}" alt="プロフィール画像">
                        @else
                            <div class="comment-placeholder"></div>
                        @endif
                            <span class="comment-user">{{ $comment->sender->name }}</span>
                    </div>
                    <p class="comment-text">{{ $comment->comment }}</p>
                    <small class="comment-time">{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                </div>
                @endforeach
            </div>
            @auth
            <form id="comment-form" class="item__comment-form" action="{{ route('comments.store') }}" method="post">
            @csrf
                <h3 class="item__comment-title">商品へのコメント</h3>
                    @if ($errors->has('comment'))
                        <p class="error-message" style="color: red;">{{ $errors->first('comment') }}</p>
                    @endif
                <textarea class="item__comment-txt" name="comment" id="comment-content"></textarea required>
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <button type="submit" class="item__comment-btn">コメントを送信する</button>
            </form>
                @else
                    <p>コメントを投稿するには <a href="{{ route('login') }}" style="text-decoration:none; color:blue;">ログイン</a> してください。</p>
                @endauth
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let starIcon = document.getElementById('star-icon');
        let myListCount = document.getElementById('mylist-count');
        let commentList = document.getElementById('comment-list');
        let commentCountTop = document.getElementById('comment-count-top');
        let commentCountBottom = document.getElementById('comment-count-bottom');
        let itemId = document.querySelector('input[name="item_id"]').value;

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


        function fetchComments() {
            fetch(`/comments/${itemId}`)
                .then(response => response.json())
                .then(data => {
                    commentList.innerHTML = "";

                    data.comments.forEach(comment => {
                        let userIcon = comment.sender.icon_path
                            ? `<img class="comment-icon" src="/storage/${comment.sender.icon_path}" alt="プロフィール画像">`
                            : `<div class="comment-placeholder"></div>`;

                        let newComment = document.createElement('div');
                        newComment.classList.add('comment-item');
                        newComment.innerHTML = `
                            <div class="comment-header">
                                ${userIcon}
                                <span class="comment-user">${comment.sender.name}</span>
                            </div>
                            <p class="comment-text">${comment.comment}</p>
                            <small class="comment-time">${comment.created_at}</small>
                        `;
                        commentList.appendChild(newComment);
                    });

                    commentCountTop.textContent = data.comment_count;
                    commentCountBottom.textContent = data.comment_count;
                })
                .catch(error => console.error('Error:', error));
        }

        setInterval(fetchComments, 10000);
    });
</script>
@endsection