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
            @php
                $revieweeId = auth()->id() === $transactions->buyer_id
                    ? $transactions->item->user->id
                    : $transactions->buyer_id;

                $hasReviewed = \App\Models\Review::where('transaction_id', $transactions->id)
                    ->where('reviewer_id', auth()->id())
                    ->exists();
            @endphp
                <div class="review-modal" style="{{ $transactions->status === 'completed' && !$hasReviewed ? '' : 'display:none;' }}">
                    <div class="review-modal-content">
                        <p class="review-modal-title">取引が完了しました。</p>
                        <p class="review-modal-txt">今回の取引相手はどうでしたか？</p>
                        <div class="star-rating">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <form id="review-form" method="POST" action="{{ route('reviews.store') }}">
                        @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transactions->id }}">
                            <input type="hidden" name="rating" id="rating-value">
                            <div class="review-submit">
                                <button type="submit">送信する</button>
                            </div>
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
        @if (session('status'))
            <div class="alert alert-success" style="color:green;">
                {{ session('status') }}
            </div>
        @endif
        @foreach ($transactionMessages as $transactionMessage)
            @php
                $isMyMessage = $transactionMessage->sender_id === Auth::id();
                $user = $transactionMessage->user;
            @endphp
            <div id="message-{{ $transactionMessage->id }}" class="message-group {{ $isMyMessage ? 'message__myself--right' : 'message__partner--left' }}">
                <div class="message__user">
                    @if ($user && $user->icon_path)
                        <img class="message__user-img" src="{{ Storage::url($user->icon_path) }}" alt="プロフィール画像">
                    @else
                        <div class="message__user-placeholder"></div>
                    @endif
                    <p class="message__user-name">{{ $user ? $user->name : '不明なユーザー' }}</p>
                </div>
                <div id="message-content-{{ $transactionMessage->id }}" class="message-content">
                    @if ($transactionMessage->message)
                        <p class="message-content__txt">{{ $transactionMessage->message }}</p>
                    @endif
                    @if ($transactionMessage->image_path)
                        <img class="message-content__img" src="{{ Storage::url($transactionMessage->image_path) }}" alt="取引画像" class="message-content__image">
                    @endif
                </div>
                    @if ($isMyMessage)
                    <div class="message-group__edit">
                        <div class="message__edit">
                            <a class="message__edit-btn" href="#" onclick="showEditForm({{ $transactionMessage->id }},
                            '{{ addslashes($transactionMessage->message) }}',
                            '{{ $transactionMessage->image_path ? Storage::url($transactionMessage->image_path) : '' }}'
                        )">編集</a>
                        </div>
                        <div class="message__delete">
                            <form method="POST" action="{{ route('transaction-message.destroy', $transactionMessage->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="message__edit-btn message__delete" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </div>
                    </div>
                        <div id="edit-form-wrapper-{{ $transactionMessage->id }}" class="message__update" style="background-color:rgb(241, 238, 181); padding:10px; display:none; max-width:600px;">
                            <form id="edit-form-{{ $transactionMessage->id }}" action="{{ route('transaction-message.update', $transactionMessage->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <textarea name="message" id="edit-text-{{ $transactionMessage->id }}">{{ $transactionMessage->message }}</textarea>
                                <img id="edit-preview-{{ $transactionMessage->id }}" class="transaction__preview-img">
                                <input id="edit-image-{{ $transactionMessage->id }}" name="image" type="file" accept="image/*" style="display:none;">
                                <label class="transaction__footer-label" for="edit-image-{{ $transactionMessage->id }}">画像を変更</label>
                                <button type="button" onclick="submitEdit({{ $transactionMessage->id }})">更新</button>
                                <button type="button" onclick="closeEditForm({{ $transactionMessage->id }})" style="background-color:gray;">キャンセル</button>
                            </form>
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
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="edit_id" id="edit-id">

                    <div class="transaction__footer--left">
                        <textarea class="transaction__footer-txt" name="message" id="message-input" placeholder="取引メッセージを記入してください"></textarea>
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
    const messageInput = document.getElementById('message-input');
    const messageList = document.querySelector('.message');
    const imageInput = document.getElementById('message-image');
    const previewImage = document.getElementById('image-preview');
    const methodInput = document.getElementById('form-method');
    const editIdInput = document.getElementById('edit-id');
    const STORAGE_KEY = 'transaction_message_draft';
    const savedDraft = localStorage.getItem(STORAGE_KEY);

    if (savedDraft) {
        messageInput.value = savedDraft;
    }

    messageInput.addEventListener('input', () => {
        localStorage.setItem(STORAGE_KEY, messageInput.value);
    });

    form.addEventListener('submit', () => {
        localStorage.removeItem(STORAGE_KEY);
    });

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
            const safeImagePath = msg.image_path ? msg.image_path.replace(/\\/g, '') : '';
            const safeMessage = msg.message ? msg.message.replace(/'/g, "\\'") : '';

            const html = `
                    <div id="message-${msg.id}" class="message-group ${isMyMessage ? 'message__myself--right' : 'message__partner--left'}">
                        <div class="message__user">
                        ${msg.user.icon_path
                            ? `<img class="message__user-img" src="/storage/${msg.user.icon_path}" alt="プロフィール画像">`
                            : `<div class="message__user-placeholder"></div>`}
                        <p class="message__user-name">${msg.user.name}</p>
                        </div>

                        <div id="message-content-${msg.id}" class="message-content">
                        ${msg.message ? `<p class="message-content__txt">${msg.message}</p>` : ''}
                        ${msg.image_path ? `<img class="message-content__img" src="/storage/${msg.image_path}" alt="取引画像">` : ''}
                        </div>

                        ${isMyMessage ? `
                        <div class="message-group__edit">
                            <div class="message__edit">
                            <a class="message__edit-btn" href="#"
                                onclick="showEditForm(${msg.id},'${safeMessage}','${safeImagePath ? '/storage/' + safeImagePath : ''}'
                            )">編集</a>
                            </div>
                            <div class="message__delete">
                            <form method="POST" action="/transaction-message/${msg.id}">
                                <input type="hidden" name="_token" value="${document.querySelector('input[name=\"_token\"]').value}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="message__edit-btn message__delete" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    `;

            messageList.insertAdjacentHTML('beforeend', html);

            messageList.scrollTop = messageList.scrollHeight;

            messageInput.value = '';
            imageInput.value = '';
            previewImage.src = '';
            previewImage.style.display = 'none';

            form.action = '{{ route('transaction.message.store', ['transaction' => $transactions->id]) }}';
            methodInput.value = 'POST';
            editIdInput.value = '';

        } catch (err) {
            console.error(err);
        }
    });
});

    function showEditForm(id, message, imageUrl) {
        document.getElementById(`edit-form-wrapper-${id}`).style.display = 'block';

        document.getElementById(`edit-text-${id}`).value = message;

        const preview = document.getElementById(`edit-preview-${id}`);
        if (imageUrl) {
            preview.src = imageUrl;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }

    const fileInput = document.getElementById(`edit-image-${id}`);
    fileInput.onchange = (e) => {
        const file = e.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    };
}

    function closeEditForm(id) {
        document.getElementById(`edit-form-wrapper-${id}`).style.display = 'none';
    }

    async function submitEdit(id) {
        const form = document.getElementById(`edit-form-${id}`);
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            if (!response.ok) {
                alert('更新に失敗しました');
                return;
            }

            const data = await response.json();

            const safeMessage = data.message.message ? data.message.message.replace(/'/g, "\\'") : '';
            const safeImagePath = data.message.image_path ? data.message.image_path.replace(/\\/g, '') : '';

            const content = document.getElementById(`message-content-${id}`);
            let html = '';
            if (safeMessage) {
                html += `<p>${safeMessage}</p>`;
            }
            if (safeImagePath) {
                html += `<img src="/storage/${safeImagePath}" alt="取引画像">`;
            }
            content.innerHTML = html;

            const editLink = document.querySelector(`#message-${id} .message__edit a`);
            editLink.setAttribute(
                'onclick',
                `showEditForm(${id}, '${safeMessage}', '${safeImagePath ? '/storage/' + safeImagePath : ''}')`
            );

            closeEditForm(id);

        } catch (err) {
            console.error(err);
            alert('更新エラー');
        }
    }
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', function(e) {

        let value = parseInt(this.dataset.value);

        document.getElementById('rating-value').value = value;

        document.querySelectorAll('.star').forEach(s => {
        s.classList.remove('full');
        });
        for (let i = 1; i <= value; i++) {
            const s = document.querySelector(`.star[data-value="${i}"]`);
            s.classList.add('full');
        }
    });
});
</script>
@endsection