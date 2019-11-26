<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;

    protected $fillable = ['type','name'];
    protected $protected = ['id'];

    /*public function typeCategoryToString(){
        switch ($this->type) {
            case 'i':
                return 'Income';
            case 'e':
                return 'Expense';
            }
            return 'Unknown';
    };*/

    protected $primaryKey = 'id';
    protected $table = 'categories';
}
