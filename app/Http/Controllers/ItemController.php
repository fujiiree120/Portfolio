<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Http\Requests\ItemUpdateStatusRequest;
use App\Http\Requests\ItemUpdateStockRequest;
use App\Http\Requests\ItemStoreRequest;

class ItemController extends Controller
{
    //
    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    public function index(){
        $title = 'ECサイト';

        $items = \App\Item::where('status', 1)->orderBy('created_at', 'desc')->get();
        return view('items.index',[
            'title' => $title,
            'items' => $items,
            'items_order' => 'created_desc',
        ]);
    }

    public function create(Request $request){
        $type = \Auth::user()->type;
        if($type === 0){
            return redirect('/items');
        }
        $title = '商品追加';
        $items = \App\Item::all();
        return view('items.create',[
            'title' => $title,
            'items' => $items,
        ]);
    }

    public function store(ItemStoreRequest $request){
        $filename = $this->make_imagefile($request->file('image'));

        $item = new Item();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->image = $filename;
        $item->status = $request->status;
        $item->save();
        return redirect('/items/{item}/create')->with('flash_message', '商品を追加しました');
    }

    public function update_status(Item $item, ItemUpdateStatusRequest $request){
        $item->status = $request->status;
        $item->save();
        return redirect('/items/{item}/create')->with('flash_message', 'ステータスを変更しました');
    }

    public function update_stock(Item $item, ItemUpdateStockRequest $request){
        $item->stock = $request->stock;
        $item->save();
        return redirect('/items/{item}/create')->with('flash_message', '在庫数を変更しました');
    }

    public function destroy(Item $item){
        $item->delete();
        return redirect('/items/{item}/create')->with('flash_message', '商品を削除しました');
    }

    private function make_imagefile($image){
        if(isset($image) === false){
            return '';
        }
        $ext = $image->guessExtension();
        $filename = str_random(20) . ".{$ext}";
        $path = $image->storeAs('photos', $filename, 'public');

        return $filename;
    }

    public function order_by(Request $request){
        if($request->items_order === 'created_desc'){
            $items = \App\Item::where('status', 1)->orderBy('created_at', 'desc')->get();
        }else if($request->items_order === 'price_asc'){
            $items = \App\Item::where('status', 1)->orderBy('price', 'asc')->get();
        }else if($request->items_order === 'price_desc'){
            $items = \App\Item::where('status', 1)->orderBy('price', 'desc')->get();
        }else{
            return redirect('/items');
        }
        return view('items.index',[
            'title' => 'ECサイト',
            'items' => $items,
            'items_order' => $request->items_order,
        ]);
    }
}
