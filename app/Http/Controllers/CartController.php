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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    public function update_amount(Cart $cart, Request $request){
        $request->validate([
            'amount' =>[
                'required',
                'integer',
                "max:{$cart->item->stock}",
                "min:1",
            ]
            ],
            [
                'amount.required' =>'数量は必須です',
                'amount.integer' =>'数字を入力してください',
                'amount.max' => '在庫数を超えています',
                'amount.min' => '1つ以上選択してください',
            ]);
        $cart->amount = $request->amount;
        $cart->save();
        return redirect('/carts')->with('flash_message', '数量を変更しました');
    }

    public function destroy(Cart $cart){
        $cart->delete();
        return redirect('/carts')->with('flash_message', 'カートから削除しました');
    }

    public function purchase(Request $request){
        $carts = \Auth::user()->carts;
        
        DB::beginTransaction();
        try {
            $order_log_id = $this->add_order_log($request->total_price);
            $this->add_order_detail($carts, $order_log_id);

            foreach($carts as $cart){  
                if($cart->item->stock < $cart->amount){
                    //バリデーションにできないか、or エラーメッセージを入れたい
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

    private function add_order_log($total_price){
        $order_log = new OrderLog();
        $order_log->user_id = \Auth::user()->id;
        $order_log->sum = $total_price;
        $order_log->save();
        $order_log_id = $order_log->id;
        return($order_log_id);
    }

    private function add_order_detail($carts, $order_log_id){
        foreach($carts as $cart){
            $order_detail = new OrderDetail();
            $order_detail->order_log_id = $order_log_id;
            $order_detail->name = $cart->item->name;
            $order_detail->image = $cart->item->image;
            $order_detail->amount = $cart->amount;
            $order_detail->purchase_price = $cart->item->price;
            $order_detail->save();
        }
    }

}
