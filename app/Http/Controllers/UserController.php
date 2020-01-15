<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function __construct()
    {
        // authというミドルウェアを設定
        $this->middleware('auth');
    }

    //管理者変更機能
    // public function change_admin(){
    //     $user_type = \Auth::user();
    //     if($user_type->type === 0){
    //         $user_type->type = 1;
    //     }else if($user_type->type === 1){
    //         $user_type->type = 0;
    //     }else{
    //         return(redirect('/items'))->with('flash_error', '管理者権限の変更に失敗しました');
    //     }
    //     $user_type->save();
    //     return(redirect('/items'));
    // }

}
