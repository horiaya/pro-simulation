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
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'name.max' => '名前は１００文字以内で設定してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.unique' => '登録できませんでした',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは８文字以上で入力してください',
            'password.max' => 'パスワードは１００文字以内で入力してください',
            'password.confirmed' => 'パスワードが一致しません',
        ];
    }
}
