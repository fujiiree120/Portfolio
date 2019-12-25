<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateStatusRequest extends FormRequest
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
            'status' => 'required|integer|regex:/[01]/'
        ];
    }

    public function messages() {
        return [
          'status.required' => 'ステータスを設定してください',
          'status.integer' => '数字を入力してください',
          'status.regex' => '数字を入力してください'
        ];
      }
}
