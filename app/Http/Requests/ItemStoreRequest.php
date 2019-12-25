<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
                'name' => 'required',
                'price' => 'required|integer|min:1',
                'stock' => 'required|integer|min:1',
                'image' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png',
                    'dimensions:min_width=100,min_height=100,max_width=600,max_height=600',
                ], // ファイルアップロードが行われ、画像ファイルでjpeg,pngのいずれか、100x100~600x600まで
                'status' => ['required',
                'regex:/[01]/',
                ],
        ];
    }

    public function messages(){
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数字を入力してください',
            'price.min' => '1円以上を入力してください',
            'stock.required' => '在庫数を入力してください',
            'stock.integer' => '在庫数は数字を入力してください',
            'stock.min' => '１つ以上入力してください',
            'status.regex' => '公開、非公開を入力してください',
            'image.required' => '画像を添付してください'
          ];
    }
}
