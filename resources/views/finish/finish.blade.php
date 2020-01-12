@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
    @foreach($errors->all() as $error)
        <p class="error">{{ $error }}</p>
        @endforeach
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
            </thead>
            <tbody>
                @forelse($carts as $cart)
                    <tr>
                        <td><img src="{{ asset('storage/photos/' . $cart->item->image) }}" class="item-image"></td>
                        <td>{{ $cart->item->name }}</td>
                        <td>{{ $cart->item->price }}円</td>
                        <td>{{ $cart->amount }}個</td>
                        <td>{{ $cart->amount * $cart->item->price }}円</td>    
                    </tr>
                @empty
                    <p>商品がありません。</p>
                @endforelse
            </tbody>
        </table>
        <p class="text-right">合計金額:{{ $total_price }}円</p>
    </div>
@endsection