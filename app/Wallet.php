<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Wallet extends Model
{
     public function toArray($request){
        return [
            'id' => $this->id,
            'email' => $this->email,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
     }
}
