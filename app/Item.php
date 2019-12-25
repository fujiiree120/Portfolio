<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = ['name', 'price', 'amount'];

    public function carts(){
        $this->hasMany('App\Cart');
    }
}
