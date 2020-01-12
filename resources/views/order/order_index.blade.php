@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>注文番号</th>
                    <th>合計金額</th>
                    <th>購入日時</th>
                    <th>購入明細</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order_logs as $order_log)
                    <tr>
                        <td>{{ $order_log->id }}</td>
                        <td>{{ $order_log->sum }}円</td>
                        <td>{{ $order_log->created_at }}</td>
                        <td>
                            <a href="{{ action('OrderController@show_order_detail', $order_log->id) }}">詳細</a>
                        </td>
                    </tr>
                @empty
                    <p>購入履歴がありません。</p>
                @endforelse
            </tbody>
         </table>
    </div>
@endsection