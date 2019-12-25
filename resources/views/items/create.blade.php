@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div>
        <form method="post" action="{{ url('/items') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <p>
                <label>商品名</label>
                <input type="text" name="name">
            </p>
            <p>
                <label>価格</label>
                <input type="text" name="price">
            </p>
            <p>
                <label>数量</label>
                <input type="text" name="stock">
            </p>
            <p>
                <label>ステータス</label>
                <select name="status">
                    <option value="1">公開</option>
                    <option value="0">非公開</option>
                </select>
            </p>
            <p>
                <label>画像</label>
                <input type="file" name="image">
            </p>
            <p>
                <input type="submit" value="商品追加" class="btn btn-primary">
            </p>
        </form>
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>操作</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
            @forelse($items as $item)
                @if($item->status === 0)
                    <tr class="close_item">
                @else
                    <tr>
                @endif
                    @if($item->image !== '')
                        <td><img src="{{ asset('storage/photos/' . $item->image) }}" class="item_image"></td>
                    @endif
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>
                        <form method="post" action="{{ action('ItemController@update_stock', $item->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <div class="form-group">
                                <input  type="text" name="stock" value="{{ $item->stock }}">
                                個
                                <input type="submit" value="変更" class="btn btn-secondary">
                            </div>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ action('ItemController@update_status', $item->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <div class="form-group">
                                @if($item->status === 1)
                                    <input type="submit" value="公開→非公開" class="btn btn-secondary">
                                    <input type="hidden" name="status" value="0">
                                @else
                                    <input type="submit" value="非公開→公開" class="btn btn-secondary">
                                    <input type="hidden" name="status" value="1">
                                @endif
                            </div> 
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{ action('ItemController@destroy', $item->id) }}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <div class="form-group">
                                <input type="submit" value="削除" class="btn btn-danger">
                            </div> 
                        </form>
                    </td>
                </tr>
            @empty
                <p>メッセージはありません。</p>
            @endforelse
            </tbody>

        </table>
    </div>
@endsection