@extends('layouts.default')

@section('content')
<div class="sell">
    <h1 class="sell__heading">商品の出品</h1>
    <div class="sell__img">
        <form action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
        @csrf
            <h3 class="sell__title">商品画像</h3>
            @error('item_image')
                <p class="error-message">{{ $message }}</p>
            @enderror
            <div id="image-preview" class="sell__img-preview">
                <input type="file" id="image" name="item_image" accept="image/*" class="sell__img-input" onchange="previewImage(event)" style="display: none;">
                <input type="hidden" name="image_temp" id="image_temp" value="{{ old('image_temp', session('image_temp')) }}">
                <label id="image-placeholder" class="sell__img-select">画像を選択する</label>

                @php
                    $imageTemp = old('image_temp', session('image_temp')) ?? '';
                @endphp

                @if(!empty($imageTemp))
                    <img src="{{ asset('storage/temp/' . $imageTemp) }}" id="image-output" class="sell__img-cover">
                @else
                    <img src="" id="image-output" class="sell__img-cover" style="display: none;">
                @endif
            </div>
            <div class="sell-detail">
                <h2 class="sell-section sell-detail__title">商品の詳細</h2>
                <h3 class="sell__category">カテゴリー</h3>
                @error('category')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <div class="sell__category-list">
                    @foreach($categories as $category)
                        <input class="sell__category-select" type="checkbox" name="category[]" value="{{ $category->id }}" id="category-{{ $category->id }}" style="display:none;" {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? 'checked' : '' }}>
                        <label class="sell__category-label {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? 'selected' : '' }}" for="category-{{ $category->id }}">{{ $category->category }}</label>
                    @endforeach
                </div>
                <h3 class="sell__condition">商品の状態</h3>
                @error('condition')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <select class="sell__condition-select" name="condition" id="">
                    <option value="" disabled {{ empty($condition) ? 'selected' : '' }}>選択してください</option>
                    @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}" {{ old('condition') == $condition->id ? 'selected' : '' }}>{{ $condition->condition }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sell-description">
                <h2 class="sell-section sell-description__title">商品名と説明</h2>
                <h3 class="sell__name">商品名</h3>
                @error('item_name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="sell__name-txt" type="text" name="item_name" value="{{ old('item_name') }}">
                <h3 class="sell__brand">ブランド名</h3>
                @error('item_brand')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="sell__brand-txt" type="text" name="item_brand" value="{{ old('item_brand') }}">
                <h3 class="sell__description">商品の説明</h3>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <textarea class="sell__condition-txt" name="description" id="">
                    {{ old('description') }}
                </textarea>
                <h3 class="sell__price">販売価格</h3>
                @error('price')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <div class="sell__price-group">
                    <span class="sell__price-prefix">¥</span>
                    <input class="sell__price-txt" type="text" name="price" value="{{ old('price') }}">
                </div>
            </div>
            <button class="sell__btn">出品する</button>
        </form>
    </div>
</div>
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        let file = event.target.files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append('item_image', file);

        //不要な画像は削除
        let oldImageTemp = document.getElementById('image_temp').value;
        if (oldImageTemp) {
            formData.append('old_image_temp', oldImageTemp);
        }

        fetch('/upload-temp-image', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (response.redirected) {
                console.error('リダイレクトが発生しました:', response.url);
                throw new Error('リダイレクトされたため JSON を取得できません。');
            }
            if (!response.ok) {
                return response.text().then(text => { throw new Error('HTTPエラー: ' + text); });
            }
            return response.json();
        })
        .then(data => {
            if (data.filename) {
                let tempPath = `/storage/temp/${data.filename}`;

                document.getElementById('image-output').src = tempPath;
                document.getElementById('image-output').style.display = 'block';
                document.getElementById('image_temp').value = data.filename;

                previewImage(event);
            } else {
                console.error('ファイル名が返されていません');
            }
        })
        .catch(error => console.error('Error:', error));
    });


    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const imagePlaceholder = document.getElementById('image-placeholder');
        const imageOutput = document.getElementById('image-output');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageOutput.src = e.target.result;
                imageOutput.style.display = 'block';

                const img = new Image();
                img.src = e.target.result;
                img.onload = function() {
                    const aspectRatio = img.width / img.height;
                    const maxWidth = 650;
                    const newHeight = maxWidth / aspectRatio;

                    imagePreview.style.height = `${Math.min(newHeight, window.innerHeight * 0.8)}px`;
                };
            };
            reader.readAsDataURL(file);
        } else {
            imageOutput.style.display = 'none';
            imageOutput.src = '';
            imagePlaceholder.style.display = 'block';
            imagePreview.style.height = '150px';
        }
    }

        document.getElementById('image-placeholder').addEventListener('click', () => {
            document.getElementById('image').click();
    });

    document.querySelectorAll('.sell__category-label').forEach(label => {
        label.addEventListener('click', () => {
            event.preventDefault();

            const checkboxId = label.getAttribute('for');
            if (!checkboxId) return;

            const checkbox = document.getElementById(checkboxId);
            if (!checkbox) return;

            checkbox.checked = !checkbox.checked;

            label.classList.toggle('selected', checkbox.checked);
        });
    });
</script>
@endsection