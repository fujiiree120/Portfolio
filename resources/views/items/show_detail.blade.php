@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <div class="detail-flex-body">
           <img src="data:image/png;base64,{{ $item->image }}"  class="detail-image">
            <div class="detail-text">
                <h1 class="border-bottom">{{ $item->name }}</h1>
                <div class="star-rating">
                    <div class="star-rating-front" style="width:{{ $item_score }}%">★★★★★</div>
                    <div class="star-rating-back">★★★★★</div>
                </div>
                <p class="detail-item-price">価格：{{ $item->price }}円</p>
                <ul class="detail-item-text-margin">
                @forelse($item_comments as $item_comment)
                    <li>{{ $item_comment->item_comments }}</li>
                @empty
                @endforelse
                </ul>
            </div>    
        </div>
        <div　class="form-group text-right">
            <form action="{{ url('/carts') }}"  method="post" class="text-right">
                {{ csrf_field() }}
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                 数量：<input  type="text" name="amount">
                <input type="submit" value="カートに追加" class="btn btn-primary">
            </form>   
        </div>  
    </div>
    <div class="container item-review-field">
        @forelse($item_reviews as $item_review)
            <div class="item-review">
                <div class="star-rating">
                    <div class="star-rating-front" style="width:{{ $item_review->item_score * 20}}%">★★★★★</div>
                    <div class="star-rating-back">★★★★★</div>
                </div>
                <p  class="border-bottom mt-2">{{ $item_review->user->name }}</p>
                <p class="font-weight-bold">{{ $item_review->item_review_title }}</p>
                <p>{{ $item_review->item_review_comment }}</p>
            </div>
        @empty
            <p>レビューはまだありません</p>
        @endforelse
        <form class=" item-review-field"  method="post" action="{{action('ItemDetailController@create_review') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="review_title">タイトル</label>
                <input name="title" type="review_title" class="form-control">
            </div>
            <div class="form-group">
                <label for="review_body">レビューを記入してください</label>
                <textarea name="body" rows="3" class="form-control" ></textarea>
            </div>
            <div class="form-group">
                <label for="review_score">点数</label>
                <select name='score'>
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
            </div>
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <button class="btn btn-primary">レビューを投稿</button>
        </form>
    </div> 
@endsection