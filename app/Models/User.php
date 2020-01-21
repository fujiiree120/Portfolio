<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function carts(){
       return $this->hasMany('App\Cart');
    }

    public function order_logs(){
        return $this->hasMany('App\OrderLog');
     }

     public function item_reviews(){
        return $this->hasMany('App\ItemReview');
     }
}
