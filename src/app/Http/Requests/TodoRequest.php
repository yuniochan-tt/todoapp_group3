<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{
    /**
     * このリクエストを許可するか（今回は誰でもOK）
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * バリデーションルール
     */
    public function rules(): array
    {
        return [
            'task' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    /**
     * エラーメッセージ（日本語）
     */
    public function messages(): array
    {
        return [
            'task.required' => 'やることを入力してください。',
            'task.max' => 'やることは:max文字以内で入力してください。',
            'due_date.date' => '期限は正しい日付で入力してください。',
        ];
    }
}
