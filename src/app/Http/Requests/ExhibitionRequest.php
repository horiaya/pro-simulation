<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'item_image' => [
                'required_without:image_temp',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'category' => 'required|array',
            'category.*' => 'exists:categories,id',
            'condition' => 'required|exists:conditions,id',
            'price' => 'required|integer|min:0;',
        ];
    }

    public function messages()
    {
        return
        [
            'item_name.required' => '商品名を入力してください',
            'item_name.max' => '文字数の上限を超えています',
            'description.required' => '商品説明を入力してください',
            'description.max' => '文字数の上限を超えています',
            'item_image.required_without' => '商品画像を選択してください',
            'item_image.image' => 'アップロードされたファイルは画像形式でなければなりません',
            'item_image.mimes' => '画像形式はJPEGまたはPNGのみ対応しています',
            'category.required' => '選択必須です',
            'condition.required' => '選択必須です',
            'price.required' => '入力必須です',
            'price.min' => '０円以上で入力してください',
        ];
    }
}
