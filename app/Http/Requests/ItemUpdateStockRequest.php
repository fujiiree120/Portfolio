<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ItemUpdateStockRequest extends FormRequest
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
            'stock' => 'required|integer|min:1'
        ];
    }

    public function messages() {
        return [
          'stock.required' => '在庫数を入力してください',
          'stock.integer' => '数字を入力してください',
          'stock.min' => '１つ以上入力してください'
        ];
      }
}
