@extends('layouts.default')

@section('content')
<div class="profile-content">
    <h1 class="profile__title">プロフィール設定</h1>
    <div class="profile-group">
        <form class="profile-group__form" action="{{ $formAction }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="profile__item">
                <div class="profile-icon-content">
                @if(auth()->user()->icon_path)
                    <img class="profile__icon" id="preview" src="{{ asset('storage/' . auth()->user()->icon_path) }}" alt="プロフィール画像">
                @else
                    <div class="profile-placeholder" id="preview-placeholder"></div>
                @endif
                    <input class="profile__icon-select" type="file" name="icon_path" id="icon" accept="image/*" style="display: none;" value="{{ asset('storage/' . auth()->user()->icon_path) }}">
                    <label class="profile__select-btn" for="icon">画像を選択する</label>
                </div>
            </div>
            <div class="profile__item">
                <p class="profile__item-title">ユーザー名</p>
                <input class="profile__item-input" type="text" name="name" value="{{ old('name', $user->name) }}">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">郵便番号</p>
                <input class="profile__item-input" type="text" name="post_code" value="{{ old('post_code', $user->post_code) }}">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">住所</p>
                <input class="profile__item-input" type="text" name="address" value="{{ old('address', $user->address) }}">
            </div>
            <div class="profile__item">
                <p class="profile__item-title">建物名</p>
                <input class="profile__item-input" type="text" name="building_name" value="{{ old('building_name', $user->building_name) }}">
            </div>
            <div class="profile__item">
                <button class="profile__form-btn">更新する</button>
            </div>
        </form>
    </div>
</div>
<script>
document.getElementById('icon').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let previewImage = document.getElementById('preview');
            let placeholder = document.getElementById('preview-placeholder');

            if (!previewImage) {
                previewImage = document.createElement('img');
                previewImage.id = 'preview';
                previewImage.classList.add('profile-placeholder');
                placeholder.replaceWith(previewImage);
            }

            previewImage.src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection