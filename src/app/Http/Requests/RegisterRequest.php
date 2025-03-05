<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'email' => 'required|string|email|max:100|unique:users,email,' . $this->user,
            'password' => 'required|string|min:8|max:100|confirmed',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'post_code' => 'nullable|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'nullable|string|max:100',
            'building_name' => 'nullable|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.unique' => '登録できませんでした',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは８文字以上で入力してください',
            'password.max' => 'パスワードは１００文字以内で入力してください',
            'password.confirmed' => 'パスワードと一致しません',
            'post_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'post_code.required' => '郵便番号を入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
