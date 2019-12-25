@extends('layouts.default')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <div class="container">
        <div class="card-deck">
            <div class="row">
                @forelse($items as $item)
                    <div class="col-6 item">
                        <div class="card h-100 text-center">
                            <div class="card-header">
                                {{ $item->name }}
                            </div>
                            <figure class="card-body">
                                @if($item->image !== '')
                                    <img src="{{ asset('storage/photos/' . $item->image) }}">
                                @endif   
                                <figcaption>                        
                                    {{ $item->price }}円
                                    @if($item->stock <= 0)
                                        <p class="text-danger">現在売り切れです。</p>
                                    @else
                                        <form action="{{ url('/carts') }}"  method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="submit" value="カートに追加" class="btn btn-primary">
                                        </form>
                                    @endif
                                </figcaption>
                            </figure>
                        </div>
                     </div>
                @empty
                    <p>メッセージはありません。</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection