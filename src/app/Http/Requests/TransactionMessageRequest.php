<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionMessageRequest extends FormRequest
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
            'message' => 'nullable|required_without:image|string|max:400',
            'image'   => 'nullable|image|mimes:jpeg,png'
        ];
    }

    public function messages()
    {
        return [
            'message.required_without' => 'メッセージまたは画像を入力してください。',
            'message.string' => 'メッセージは文字列で入力してください。',
            'message.max' => 'メッセージは400文字以内で入力してください。',
            'image.image' => 'アップロードするファイルは画像形式にしてください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像のサイズは5MB以内にしてください。',
        ];
    }
}
