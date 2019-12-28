@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>購入数</th>

                </tr>
            </thead>
            <tbody>
                @forelse($order_details as $order_detail)
                    <tr>
                        <td>{{ $order_detail->name }}</td>
                        <td>{{ $order_detail->purchase_price }}円</td>
                        <td>{{ $order_detail->amount }}</td>
                    </tr>
                @empty
                    <p>購入履歴がありません。</p>
                @endforelse
            </tbody>
         </table>
         <p class="text-right">合計金額:{{ $order_price }}円</p>
    </div>
@endsection