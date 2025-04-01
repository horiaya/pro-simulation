<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'payment' => 'required|exists:payments,id',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ユーザーがいません',
            'item_id.required' => '商品が無効です',
            'payment.required' => '支払い方法を選択してください',
            'payment.exists'   => '選択された支払い方法が無効です',
        ];
    }
}
