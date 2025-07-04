<p>出品者様へ</p>

<p>{{ $transaction->buyer->name }}さんが取引を完了しました。</p>

<p>商品名: {{ $transaction->item->item_name }}</p>

<p style="margin-bottom:30px;">取引画面からレビューをお願いします</p>
<a href="{{ route('transaction.index', ['itemId' => $transaction->item_id]) }}" style="text-decoration:none; background-color:#ff5151;; color:white; padding:5px 10px; margin:30px 10px;">取引ページを開く</a>

