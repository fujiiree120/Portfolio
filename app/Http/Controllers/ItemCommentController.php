<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\ItemComment;
use App\Http\Requests\UpdateItemCommentRequest;
class ItemCommentController extends Controller
{

    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    
    //$item_commentにItemCommentを格納し、item_comment.blade.phpでコメント画面に移行
    public function index(Request $request, $id)
    {
        $title = '商品コメント';
        $item_comments = ItemComment::where('item_id', $id)->get();
        return view('items.item_comment',[
            'title' => $request->name,
            'item_comments' => $item_comments,
            'item_id' => $id,
        ]);
    }

    public function store(UpdateItemCommentRequest $request, $id)
    {
        $item_comment = new ItemComment();
        $item_comment->item_id = $id;
        $item_comment->item_comments = $request->item_comment;
        $item_comment->save();

        return redirect('/items/{item}/create')->with('flash_message', '商品コメントを追加しました');
    }

    public function update_item_comment(ItemComment $item_comment, UpdateItemCommentRequest $request)
    {
        $item_comment->item_comments = $request->item_comment;
        $item_comment->save();
        return redirect('/items/{item}/create')->with('flash_message', '商品コメントを変更しました');
    }

    public function destroy(ItemComment $item_comment)
    {
        $item_comment->delete();
        return redirect('/items/{item}/create')->with('flash_message', '商品コメントを削除しました');
    }

}
