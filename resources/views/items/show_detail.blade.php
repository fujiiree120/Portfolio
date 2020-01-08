@extends('layouts.default')

@section('title', $title)

@section('content')

    <div class="container">
        <div class="detail-flex-body">
           <img src="{{ asset('storage/photos/' . $image) }}" class="detail-image">
            <div class="detail-text">
                <h1 class="border-bottom">{{ $name }}</h1>
                <p class="detail-item-price detail-item-text-margin">価格：{{ $price }}円</p>
                <ul class="detail-item-text-margin">
                    <li>あああああああああああああああああああｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄｄあああああああああああ</li>
                    <li>いいいいいいいいいいいいいいいいいいいいいいいいいいいいいい</li>
                    <li>いいいいいいいいいいいいいいいいいいいいいいいいいいいいいい</li>
                </ul>
            </div>    
        </div>
        <div　class="form-group text-right">
            <form action="{{ url('/carts') }}"  method="post" class="text-right">
                {{ csrf_field() }}
                <input type="hidden" name="item_id" value="{{ $item_id }}">
                 数量：<input  type="text" name="amount">
                <input type="submit" value="カートに追加" class="btn btn-primary">
            </form>   
        </div>     
    </div>
@endsection