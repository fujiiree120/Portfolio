<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = ['name', 'price', 'amount'];

    public function carts(){
        return $this->hasMany('App\Cart');
    }

    public function item_comments(){
        return $this->hasMany('App\ItemComment');
    }
}
