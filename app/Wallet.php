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
    protected $attributes = [
        'id', 'email', 'balance', 'created_at', 'updated_at'
    ];
}
