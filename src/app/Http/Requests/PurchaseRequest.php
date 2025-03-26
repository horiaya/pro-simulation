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
            'post_code' => 'required',
            'address' => 'required',
            'building_name' => 'nullable',
            'payment' => 'required|exists:payments,id',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ユーザーがいません',
            'item_id.required' => '商品が無効です',
            'post_code.required' => '郵便番号が未登録です',
            'address.required' => '住所が未登録です',
            'payment.required' => '支払い方法を選択してください',
        ];
    }
}
