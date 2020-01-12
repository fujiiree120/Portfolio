<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemComment;
use App\ItemReview;

use App\Http\Requests\ItemReviewRequest;

class ItemDetailController extends Controller
{
    //
    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    public function show_detail($id)
    {
        //$item_idを取得し商品詳細画面を表示
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

    public function create_review(ItemReviewRequest $request)
    {
        //Comentモデルを作成
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
        //該当商品のコメントを$item_reviewに格納し表示する
        $item_review = ItemReview::where('item_id', $id)->get();
        return $item_review;
    }

    private function get_item_score($item_reviews)
    {
        //商品の平均スコアを取得する
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
}
