@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <div>
            <form method="get" action="{{action('ItemController@order_by') }}"  class="text-right" name="myform" id = "my_form">
                {{ csrf_field() }}
                <select name='items_order' id='order_by'>
                    <option value="created_desc" @if($items_order == 'created_desc') selected @endif>新着順</option>
                    <option value="price_asc"  @if($items_order == 'price_asc') selected @endif>安い順</option>
                    <option value="price_desc" @if($items_order == 'price_desc') selected @endif>高い順</option>
                </select>
                <script type="text/javascript" src="/js/index.js"></script>
            </form>
        </div>
        <div class="card-deck">
            <div class="row">
                @forelse($items as $item)
                    <div class="col-6 items-field">
                        <div class="card h-100 text-center">
                            <div class="card-header">
                                {{ $item->name }}
                            </div>
                            <figure class="card-body">
                                @if($item->image !== '')
                                    <img src="{{ asset('storage/photos/' . $item->image) }}" class="index-image">
                                @endif   
                                <figcaption>                        
                                    {{ $item->price }}円
                                    @if($item->stock <= 0)
                                        <p class="text-danger">現在売り切れです。</p>
                                    @else
                                        <form action="{{ url('/carts') }}"  method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="amount" value="1">
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="submit" value="カートに追加" class="btn btn-primary">
                                        </form>
                                        <form action="{{ action('ItemController@show_detail', $item->id) }}"  method="get">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="submit" value="詳細画面" class="btn btn-secondary  item-detail-btn">
                                        </form>
                                    @endif
                                </figcaption>
                            </figure>
                        </div>
                     </div>
                @empty
                    <p>商品はありません。</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection