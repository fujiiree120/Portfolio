<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemComment;

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
        $get_open_items = $this->get_open_items();
        $title = 'ECサイト';
        $items = $get_open_items->orderBy('created_at', 'desc')->get();
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
        //Itemに商品を追加する
        $item = new Item();
        $item->name = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->image = $filename;
        $item->status = $request->status;
        $item->save();

        //ItemComentにコメントを追加する
        $item_comment = new ItemComment();
        $item_comment->item_id = $item->id;
        $item_comment->item_comments = $request->item_comment;
        $item_comment->save();

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

    public function order_by(Request $request){
        $get_open_items = $this->get_open_items();
        if($request->items_order === 'created_desc'){
            $items = $get_open_items->orderBy('created_at', 'desc')->get();
        }else if($request->items_order === 'price_asc'){
            $items = $get_open_items->orderBy('price', 'asc')->get();
        }else if($request->items_order === 'price_desc'){
            $items = $get_open_items->orderBy('price', 'desc')->get();
        }else{
            return redirect('/items');
        }
        return view('items.index',[
            'title' => 'ECサイト',
            'items' => $items,
            'items_order' => $request->items_order,
        ]);
    }

    public function show_detail($id){
        $item = \App\Item::where('id', $id)->first();
        return view('items.show_detail',[
            'title' => '商品詳細',
            'item' => $item,
        ]);
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

    private function get_open_items(){
        //公開されてる商品を$get_open_itemに格納し返り値とする
        $get_open_items = \App\Item::where('status', 1);
        return $get_open_items;
    }

}
