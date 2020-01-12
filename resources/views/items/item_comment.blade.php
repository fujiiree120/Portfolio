@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div>
        <form method="post" action="{{ action('ItemCommentController@store_comment', $item_id) }}">
            {{ csrf_field() }}
            {{ method_field('patch') }}
            <div class="form-group">
                <input  type="text" name="item_comment" class="text-field">
                <input type="submit" value="コメントを追加する" class="btn btn-primary">
            </div> 
        </form>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>商品コメント</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
            @forelse($item_comments as $item_comment)
                <tr>
                    <td>
                        <form method="post" action="{{ action('ItemCommentController@update_item_comment', $item_comment->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <div class="form-group">
                                <input  type="text" name="item_comment" value="{{ $item_comment->item_comments }}" class="text-field"> 
                                <input type="submit" value="編集" class="btn btn-secondary">
                            </div>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ action('ItemCommentController@destroy_comment', $item_comment->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <div class="form-group">
                            <input  type="hidden" name="item_comment_id" value="{{ $item_comment->id }}">
                                <input type="submit" value="削除" class="btn btn-danger">
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <p>商品コメントはありません。</p>
            @endforelse
            </tbody>

        </table>
    </div>
@endsection