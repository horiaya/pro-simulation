<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'comment' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return
        [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => '文字数の上限を超えています',
        ];
    }
}
