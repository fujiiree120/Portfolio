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

    public function index(Request $request)
    {
        $get_open_items = $this->get_open_items();
        $title = 'ECサイト';
        $items_order = $this->order_by($request->items_order);
        $items = $get_open_items->orderBy($items_order[0], $items_order[1])->get();
        return view('items.index',[
            'title' => $title,
            'items' => $items,
            'items_order' => $items_order[2],
        ]);
    }

    public function order_by($request)
    {
        if($request === 'price_asc'){
            $order_subject = 'price';
            $order_by = 'asc';
            $displayed_order_text = 'price_asc';
        }else if($request === 'price_desc'){
            $order_subject = 'price';
            $order_by = 'desc';
            $displayed_order_text = 'price_desc';
        }else{
            $order_subject = 'created_at';
            $order_by = 'desc';
            $displayed_order_text = 'created_at';
        }
        return $items_order = array($order_subject, $order_by, $displayed_order_text);
    }

    public function create(Request $request)
    {
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

    public function store(ItemStoreRequest $request)
    {
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

    public function update_status(Item $item, ItemUpdateStatusRequest $request)
    {
        $item->status = $request->status;
        $item->save();
        return redirect('/items/{item}/create')->with('flash_message', 'ステータスを変更しました');
    }

    public function update_stock(Item $item, ItemUpdateStockRequest $request)
    {
        $item->stock = $request->stock;
        $item->save();
        return redirect('/items/{item}/create')->with('flash_message', '在庫数を変更しました');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect('/items/{item}/create')->with('flash_message', '商品を削除しました');
    }

    public function show_detail($id)
    {
        $item = \App\Item::where('id', $id)->first();
        $item_comments = \App\ItemComment::where('item_id', $id)->get();
        return view('items.show_detail',[
            'title' => '商品詳細',
            'item' => $item,
            'item_comments' => $item_comments,
        ]);
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        if(empty($keyword)){
            return redirect('/items');
        }
        $get_open_items = $this->get_open_items();
        $items = $get_open_items->where('name', 'like', '%'.$keyword.'%')->get();
        //商品コメントもキーワード対象に入れる
        return view('items.index',[
            'title' => 'ECサイト',
            'items' => $items,
            'items_order' => 'created_desc',
        ]);
    }

    private function make_imagefile($image)
    {
        if(isset($image) === false){
            return '';
        }
        $ext = $image->guessExtension();
        $filename = str_random(20) . ".{$ext}";
        $path = $image->storeAs('photos', $filename, 'public');

        return $filename;
    }

    private function get_open_items()
    {
        //公開されてる商品を$get_open_itemに格納し返り値とする
        $get_open_items = \App\Item::where('status', 1);
        return $get_open_items;
    }

}
