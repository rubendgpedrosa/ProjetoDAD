<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
   public function Wallet(){
         return $this->belongsTo('App\Movement','wallet_id','transfer_wallet_id');
      }
      public function Category(){
        return $this->belongsTo('App\Category','category_id');
      }

protected $fillable=[
'wallet_id',
'type',
'transfer',
'category_id',
'transfer_movement_id',
'transfer_wallet_id'
,'type_payment',
'mb_entity_code',
'mb_payment_reference',
'iban',
'description',
'source_description',
'date',
'start_balance','
end_balance',
'value'


];

}
