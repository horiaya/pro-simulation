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
                    <small id="comment-count-top" class="item__count-small">{{ $commentCount ?? 0 }}</small>
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
            <h2 class="item__comment-heading">コメント(<span id="comment-count-bottom">{{ $commentCount ?? 0 }}</span>)</h2>
            @if ($item->comments->isNotEmpty())
            <div id="comment-list" class="item__comment-list">
                @foreach ($comments as $comment)
                    <p class="item__comment-profile"><img class="item__comment-profile-icon" id="preview" src="{{ asset('storage/' . $comment->sender->icon_path) }}" alt="プロフィール画像">{{ $comment->user->name }}</p>
                    <p class="item__comment-list-txt">{{ $comment->comment }}</p>
                @endforeach
            </div>
            @endif
            @auth
            <form id="comment-form" class="item__comment-form" action="{{ route('comments.store') }}" method="post">
            @csrf
                <h3 class="item__comment-title">商品へのコメント</h3>
                @error('comment')
                    <p class="error-message">{{$errors->first('comment')}}</p>
                @enderror
                <textarea class="item__comment-txt" name="comment" id="comment-content"></textarea>
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
        let commentForm = document.getElementById('comment-form');
        let commentList = document.getElementById('comment-list');
        let commentCountTop = document.getElementById('comment-count-top');
        let commentCountBottom = document.getElementById('comment-count-bottom');


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

        if (commentForm) {
            commentForm.addEventListener('submit', function (e) {
                e.preventDefault();

                let formData = new FormData();
                formData.append('comment', document.getElementById('comment-content').value.trim());
                formData.append('item_id', document.getElementById('comment-form').querySelector('input[name="item_id"]').value);

                fetch("{{ route('comments.store') }}", {
                    method: "POST",
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.comment) {
                        let newComment = document.createElement('div');
                        newComment.classList.add('comment');
                        newComment.innerHTML = `
                            <p>${data.comment.comment}</p>
                        `;
                        commentList.prepend(newComment);

                        commentCountTop.textContent = data.comment_count;
                        commentCountBottom.textContent = data.comment_count;

                        commentForm.reset();
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
@endsection