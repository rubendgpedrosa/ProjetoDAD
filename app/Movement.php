<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Movement extends Model
{
    use Notifiable;

    protected $fillable = ['wallet_id','type','transfer','transfer_movement_id','type_payment','category_id',
    'iban','mb_entity_code','mb_payment_reference','descrition','source_description','date','start_balance','end_balance','value'];
    protected $protected = ['id'];

    protected $primaryKey = 'id';
    protected $table = 'movements';

    //By default, Eloquent expects created_at and updated_at columns to exist on your tables.
    //If you do not wish to have these columns automatically managed by Eloquent, set the $timestamps property on your model to false.
    public $timestamps = false;
}
