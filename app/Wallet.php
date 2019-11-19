<?php

namespace App;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Model;


class Wallet extends Model
{
    protected $table = 'wallets';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public function User(){
     return $this->belongsTo('App\User');
     }
    public function Movements(){
    return $this->hasMany('App\Movement');
    }


    protected $attributes = [
        'id', 'email', 'balance', 'created_at', 'updated_at'
    ];
}
