@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carts as $cart)
                    <tr>
                        <td><img src="data:image/png;base64,{{ $cart->item->image }}"  class="item-image"></td>
                        <td>{{ $cart->item->name }}</td>
                        <td>{{ $cart->item->price }}円</td>
                        <td>
                            <form method="post"  action="{{ action('CartController@update_amount', $cart->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('patch') }}
                                    <div class="form-group">
                                        数量：<input  type="text" name="amount" value="{{ $cart->amount }}">
                                        <input type="submit" value="変更" class="btn btn-secondary">
                                    </div>
                            </form>
                        </td>
                        <td>{{ $cart->amount * $cart->item->price }}円</td>
                        <td>
                            <form method="post" action="{{ action('CartController@destroy_cart', $cart->id) }}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <div class="form-group">
                                    <input type="submit" value="削除" class="btn btn-danger">
                                </div> 
                            </form>
                        </td>      
                    </tr>
                @empty
                    <p>商品がありません。</p>
                @endforelse
            </tbody>
         </table>
         <p class="text-right">合計金額:{{ $total_price }}円</p>
        <form method="post" action="{{ action('CartController@purchase', \Auth::user()->id) }}">
            {{ csrf_field() }}
            <input type="submit" value="購入" class="btn btn-block btn-primary">
            <input type="hidden" value="{{ $total_price }}" name="total_price" >
        </form>
    </div>
@endsection