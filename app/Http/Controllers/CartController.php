<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\OrderLog;
use App\OrderDetail;
use App\Http\Requests\ItemUpdateAmountRequest;
use DB;
class CartController extends Controller
{
    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    public function index()
    {
        //itemテーブルと結びつけて表示
        $title = 'カート';
        $total_price = 0;
        $carts = \Auth::user()->carts;
        foreach($carts as $cart){
            $total_price += $cart->item->price * $cart->amount;
        }
        return view('carts.index',[
        'title' => $title,
        'carts' => $carts,
        'total_price' => $total_price,
        ]);
    }

    public function store(ItemUpdateAmountRequest $request)
    {
        //$cartにユーザーの商品情報を格納している
        $cart = \Auth::user()->carts->where('item_id', $request->item_id)->first();
        if(empty($cart)){
            $cart = new Cart();
            $cart->user_id = \Auth::user()->id;
            $cart->item_id = $request->item_id;
            $cart->amount = $request->amount;
        }else{
            //amountを更新する処理
           $cart->amount += $request->amount;
        }
        $cart->save();
        return redirect('/items')->with('flash_message', '商品をカートに入れました');
    }

    public function update_amount(Cart $cart, ItemUpdateAmountRequest $request)
    {
        //購入量>在庫数の場合エラーとする
        if($this->is_valid_item_stock($cart, $request->amount) === false){
            return redirect('/carts')->with('flash_error', '在庫数が足りません');
        }
        $cart->amount = $request->amount;
        $cart->save();
        return redirect('/carts')->with('flash_message', '数量を変更しました');
    }

    public function destroy_cart(Cart $cart)
    {
        $cart->delete();
        return redirect('/carts')->with('flash_message', 'カートから削除しました');
    }

    public function purchase(Request $request)
    {
        //カートの商品を購入する処理と同時にOrderLogモデルの追加も行う
        $carts = \Auth::user()->carts;
        DB::beginTransaction();
        try {
            if($carts->isEmpty()){
                return redirect('/carts')->with('flash_error', 'カートに商品がありません');
            }
            $order_log_id = $this->add_order_log($request->total_price);
            $this->add_order_detail($carts, $order_log_id);

            foreach($carts as $cart){  
                if($this->is_valid_item_stock($cart, $cart->amount) === false){
                    return redirect('/carts')->with('flash_error', '在庫数が足りません');
                }
                $cart->item->stock -= $cart->amount;
                $cart->item->save();
                $cart->delete();
            }
            DB::commit();
        } catch (\PDOException $e){
            DB::rollBack();
        }
        return view('finish.finish',[
            'title' => '購入完了',
            'carts' => $carts,
            'total_price' => $request->total_price,
        ]);
    }

    //購入履歴モデルを作成
    private function add_order_log($total_price)
    {
        $order_log = new OrderLog();
        $order_log->user_id = \Auth::user()->id;
        $order_log->sum = $total_price;
        $order_log->save();
        $order_log_id = $order_log->id;
        return($order_log_id);
    }

    //購入明細モデルを作成
    private function add_order_detail($carts, $order_log_id)
    {
        foreach($carts as $cart){
            $order_detail = new OrderDetail();
            $order_detail->order_log_id = $order_log_id;
            $order_detail->name = $cart->item->name;
            $order_detail->amount = $cart->amount;
            $order_detail->purchase_price = $cart->item->price;
            $order_detail->save();
        }
    }

    //購入量が在庫数よりも多いかチェック
    private function is_valid_item_stock($cart, $cart_amount)
    {
        return $cart->item->stock >= $cart_amount;
    }

}
