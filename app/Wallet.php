<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
            'email',
            'balance',
            'id',
            'created_at',
            'updated_at',
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }
}
