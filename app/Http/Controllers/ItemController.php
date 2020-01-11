<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemComment;
use App\ItemReview;

use App\Http\Requests\ItemUpdateStatusRequest;
use App\Http\Requests\ItemUpdateStockRequest;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemReviewRequest;

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
        $items = Item::all();
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
        $item_reviews = $this->get_item_review($id);
        $item_score = $this->get_item_score($item_reviews);
        $item = Item::where('id', $id)->first();
        $item_comments = ItemComment::where('item_id', $id)->get();
        return view('items.show_detail',[
            'title' => '商品詳細',
            'item' => $item,
            'item_comments' => $item_comments,
            'item_reviews' => $item_reviews,
            'item_score' => $item_score,
        ]);
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        if(empty($keyword)){
            return redirect('/items');
        }
        $get_open_items = $this->get_open_items();

        $items = $get_open_items->whereHas('item_comments', function ($query) use ($keyword){
            $query->where('item_comments',  'like', '%'.$keyword.'%');
        })->orWhere('name', 'like', '%'.$keyword.'%')->get();
        return view('items.index',[
            'title' => 'ECサイト',
            'items' => $items,
            'items_order' => 'created_desc',
        ]);
    }

    public function create_review(ItemReviewRequest $request)
    {
        $item_review = new ItemReview();
        $item_review->item_id = $request->item_id;
        $item_review->user_id = \Auth::user()->id;
        $item_review->item_score = $request->score;
        $item_review->item_review_title = $request->title;
        $item_review->item_review_comment = $request->body;
        $item_review->save();

        return redirect('/items')->with('flash_message', '商品レビューを投稿しました');
    }

    private function get_item_review($id)
    {
        $item_review = ItemReview::where('item_id', $id)->get();
        return $item_review;
    }

    private function get_item_score($item_reviews)
    {
        $item_score = 0;
        $item_score_division = 0;
        foreach($item_reviews as $item_review){
            $item_score += $item_review->item_score;
            $item_score_division ++;
        }
        if($item_score <= 0){
            $item_score = 0;
        }else{
            $item_score = round($item_score * 20 / $item_score_division);
        }
        return $item_score;
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
        $get_open_items = Item::where('status', 1);
        return $get_open_items;
    }

}
