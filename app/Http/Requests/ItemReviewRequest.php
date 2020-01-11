<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemReviewRequest extends FormRequest
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
            //
            'title' => 'required',
            'body' => 'required',
            'score' => [
                'required',
                'integer',
                'regex:/[1-5]/',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'タイトルを入力してください',
            'body.required' => 'コメントを入力してください',
            'score.required' => '点数を入れてください',
            'score.integer' => '点数が不正です',
            'score.regex' => '点数が不正です'
          ];
    }
}
