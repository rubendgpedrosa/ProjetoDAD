<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;

    protected $fillable = ['type','name'];
    protected $protected = ['id'];

    protected $primaryKey = 'id';
    protected $table = 'categories';
}
