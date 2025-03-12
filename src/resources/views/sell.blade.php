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
                <label id="image-placeholder" class="sell__img-select">画像を選択する</label>
                <img src="{{ session('image_temp') ? asset('storage/' . session('image_temp')) : '' }}" id="image-output" class="sell__img-cover" style="{{ session('item_temp') ? 'display: block;' : 'display: none;' }}">
                @error('item_image')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            <div class="sell-detail">
                <h2 class="sell-section sell-detail__title">商品の詳細</h2>
                <h3 class="sell__category">カテゴリー</h3>
                @error('category')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <div class="sell__category-list">
                    @foreach($categories as $category)
                        <input class="sell__category-select" type="checkbox" name="category[]" value="{{ $category->id }}" id="category-{{ $category->id }}" style="display:none;">
                        <label class="sell__category-label" for="category-{{ $category->id }}">{{ $category->category }}</label>
                    @endforeach
                </div>
                <h3 class="sell__condition">商品の状態</h3>
                @error('condition')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <select class="sell__condition-select" name="condition" id="">
                    <option value="" disabled {{ empty($condition) ? 'selected' : '' }}>選択してください</option>
                    @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sell-description">
                <h2 class="sell-section sell-description__title">商品名と説明</h2>
                <h3 class="sell__name">商品名</h3>
                @error('item_name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="sell__name-txt" type="text" name="item_name">
                <h3 class="sell__brand">ブランド名</h3>
                @error('item_brand')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <input class="sell__brand-txt" type="text" name="item_brand">
                <h3 class="sell__description">商品の説明</h3>
                @error('description')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <textarea class="sell__condition-txt" name="description" id=""></textarea>
                <h3 class="sell__price">販売価格</h3>
                @error('price')
                    <p class="error-message">{{ $message }}</p>
                @enderror
                <div class="sell__price-group">
                    <span class="sell__price-prefix">¥</span>
                    <input class="sell__price-txt" type="text" name="price">
                </div>
            </div>
            <button class="sell__btn">出品する</button>
        </form>
    </div>
</div>
<script>
    function previewImage(event) {
        const imagePreview = document.getElementById('image-preview');
        const imagePlaceholder = document.getElementById('image-placeholder');
        const imageOutput = document.getElementById('image-output');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageOutput.src = e.target.result;
                imagePlaceholder.style.display = 'none';
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

    /*document.getElementById('image-preview').addEventListener('click', () => {
        document.getElementById('image').click();
    });*/

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