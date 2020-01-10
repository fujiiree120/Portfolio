<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderDetail;
use App\OrderLog;

class OrderController extends Controller
{
    //
    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    public function index()
    {
        $title = '購入履歴';
        $order_logs = \Auth::user()->order_logs;
        return view('order.order_index',[
            'title' => $title,
            'order_logs' => $order_logs,
        ]);
        
    }
    
    public function show($id)
    {

        $title = '購入明細';
        $order_details = OrderDetail::where('order_log_id', $id)->get();
        $order_price = OrderLog::where('id', $id)->first();
        return view('order.order_show',[
            'title' => $title,
            'order_details' => $order_details,
            'order_price' => $order_price->sum,
        ]);
        
    }

}
