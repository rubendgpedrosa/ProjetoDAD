<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Wallet extends Model
{

    protected $table = 'wallets';
    protected $primaryKey = 'email';
    public $incrementing = false;


    protected $fillable = [
        'email',
        'balance',
        'id',
        'created_at',
        'updated_at',
    ];

}
