<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'icon_path' => 'nullable|image|mimes:jpeg,png|max:2048',
            'post_code' => 'nullable|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'nullable|string|max:100',
            'building_name' => 'nullable|string|max:100',
        ];
    }

    public function message()
    {
        return [
            'name' => '名前を入力してください',
            'name.max' => '名前は１００文字以内で設定してください',
            'post_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}

