@extends('layouts.default')

@section('hide-header-nav')
<div></div>
@endsection

@section('main-class', 'no-padding')

@section('content')
<div class="transaction">
    @php
        $isBuyer = $user->id === $transactions->buyer_id;

        $partner = $isBuyer ? $transactions->item->user : $transactions->buyer;
    @endphp
    <div class="transaction__group--left">
        <p class="transaction__side-title">その他の取引</p>
        <div class="transaction__others-list">
            @foreach($otherTransactions as $other)
                <div class="transaction__others-list-item">
                    <a class="transaction__others-link" href="{{ route('transaction.index', ['itemId' => $other->item_id]) }}">{{ $other->item->item_name }}</a>
                </div>
            @endforeach
        </div>
    </div>
    <div class="transaction__group--right">
        <div class="transaction__head">
            <div class="transaction__head-heading">
            @if($partner && $partner->icon_path)
                <img class="transaction__head-icon" src="{{ Storage::url($transaction->buyer->icon_path) }}" alt="プロフィール画像">
            @else
                <div class="transaction__head-placeholder"></div>
            @endif
                <h1 class="transaction__head-title">{{ $partner->name }}さんとの取引画面</h1>
            </div>
            <div class="transaction__head-side">
            @if (auth()->id() === $transactions->buyer_id)
                <form method="POST" action="{{ route('transactions.complete', $transactions->id) }}">
                @csrf
                    <button class="transaction__head-btn" style="{{ $transactions->status === 'completed' ? 'display:none;' : '' }}">取引を完了する</button>
                </form>
            @endif
                <div class="review-modal" style="{{ $transactions->status === 'completed' ? '' : 'display:none;' }}">
                    <div class="review-modal-content">
                        <p class="review-modal-title">取引が完了しました。</p>
                        <p class="review-modal-txt">今回の取引相手はどうでしたか？</p>
                        <div class="star-rating">
                            <span class="star" data-value="0.5">
                                <i class="fa-regular fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                                <i class="fa-solid fa-star"></i>
                            </span>
                            <span class="star" data-value="1"></span>
                            <span class="star" data-value="1.5"></span>
                            <span class="star" data-value="2"></span>
                            <span class="star" data-value="2.5"></span>
                            <span class="star" data-value="3"></span>
                            <span class="star" data-value="3.5"></span>
                            <span class="star" data-value="4"></span>
                            <span class="star" data-value="4.5"></span>
                            <span class="star" data-value="5"></span>
                        </div>
                        <form id="review-form" method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transactions->id }}">
                            <input type="hidden" name="reviewee_id" value="{{ $transactions->item->user->id }}">
                            <input type="hidden" name="rating" id="rating-value">
                            <button type="submit">送信する</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="transaction__item">
            <div class="transaction__item-img">
                <img class="transaction__item-img--left" src="{{ Storage::url('item_image/' . $transactions->item->item_image) }}" alt="商品画像">
            </div>
            <div class="transaction-group__item">
                <div class="transaction__item--large">
                    <h2 class="transaction__item-name">{{$transactions->item->item_name}}</h2>
                </div>
                <div class="transaction__item--small">
                    <p class="transaction__item-price">¥{{ number_format($transactions->item->price) }}</p>
                </div>
            </div>
        </div>
        <div class="message">
        @foreach ($transactionMessages as $transactionMessage)
            @php
                $isMyMessage = $transactionMessage->sender_id === Auth::id();
                $user = $transactionMessage->user;
            @endphp
            <div class="message-group {{ $isMyMessage ? 'message__myself--right' : 'message__partner--left' }}">
                <div class="message__user">
                    @if ($user && $user->icon_path)
                        <img class="message__user-img" src="{{ Storage::url($user->icon_path) }}" alt="プロフィール画像">
                    @else
                        <div class="message__user-placeholder"></div>
                    @endif
                    <p class="message__user-name">{{ $user ? $user->name : '不明なユーザー' }}</p>
                </div>
                <div class="message-content">
                    @if ($transactionMessage->message)
                        <p class="message-content__txt">{{ $transactionMessage->message }}</p>
                    @endif
                    @if ($transactionMessage->image_path)
                        <img class="message-content__img" src="{{ Storage::url($transactionMessage->image_path) }}" alt="取引画像" class="message-content__image">
                    @endif
                </div>
                    @if ($isMyMessage)
                        <div class="message__edit">
                            <a class="message__edit-btn" href="#">編集</a>
                            <a class="message__edit-btn message__delete" href="#">削除</a>
                        </div>
                    @endif
            </div>
        @endforeach
        </div>
        <div class="transaction__footer">
        @error('message')
            <p class="error-message">{{ $message }}</p>
        @enderror
        @error('image')
            <p class="error-message">{{ $message }}</p>
        @enderror
            <div class="transaction-group__footer">
                <form id="transaction-message-form" class="transaction__footer-form" action="{{ route('transaction.message.store', ['transaction' => $transactions->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="transaction__footer--left">
                        <input class="transaction__footer-txt" type="text" name="message" placeholder="取引メッセージを記入してください">
                    </div>
                    <div class="transaction__footer--right" >
                        <img id="image-preview" class="transaction__preview-img">
                        <input id="message-image" name="image" class="transaction__footer-img" type="file" accept="image/*" style="display:none;">
                        <label class="transaction__footer-label" for="message-image">画像を追加</label>
                        <button type="submit" class="transaction__footer-form-btn">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('transaction-message-form');
    const messageInput = form.querySelector('input[name="message"]');
    const messageList = document.querySelector('.message');
    const imageInput = document.getElementById('message-image');
    const previewImage = document.getElementById('image-preview');

    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            previewImage.src = URL.createObjectURL(file);
            previewImage.style.display = 'block';
        } else {
            previewImage.src = '';
            previewImage.style.display = 'none';
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        /*for (const [key, value] of formData.entries()) {
  console.log(key, value);
}*/

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    const messages = Object.values(data.errors).flat().join('\n');
                    alert(messages);
                } else {
                    alert('送信失敗');
                }
                throw new Error('送信失敗');
            }

            const msg = data.message;

            const isMyMessage = msg.sender_id == {{ Auth::id() }};

            const html = `
                <div class="message-group ${isMyMessage ? 'message__myself--right' : 'message__partner--left'}">
                    <div class="message__user">
                        ${msg.user.icon_path
                            ? `<img class="message__user-img" src="/storage/${msg.user.icon_path}" alt="プロフィール画像">`
                            : `<div class="message__user-placeholder"></div>`
                        }
                        <p class="message__user-name">${msg.user.name}</p>
                    </div>
                    <div class="message-content">
                        ${msg.message ? `<p>${msg.message}</p>` : ''}
                        ${msg.image_path ? `<img src="/storage/${msg.image_path}" alt="取引画像" class="message-content__img">` : ''}
                    </div>
                    ${isMyMessage ? `
                        <div class="message__edit">
                            <a class="message__edit-btn" href="#">編集</a>
                            <a class="message__edit-btn message__delete" href="#">削除</a>
                        </div>` : ''}
                </div>
            `;

            messageList.insertAdjacentHTML('beforeend', html);

            messageList.scrollTop = messageList.scrollHeight;

            messageInput.value = '';
            imageInput.value = '';
            previewImage.src = '';
            previewImage.style.display = 'none';

        } catch (err) {
            console.error(err);
        }
    });
});
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function() {
    const rating = this.dataset.value;
    document.getElementById('rating-value').value = rating;

    document.querySelectorAll('.star').forEach(s => {
        s.classList.toggle('active', parseFloat(s.dataset.value) <= rating);
        });
    });
});
</script>
@endsection