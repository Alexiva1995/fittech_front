<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public $table = "foods";

    protected $fillable = [
        'cant', 'name', 'measure', 'type_measure', 'eq', 'calories', 'protein', 'greases', 'carbo',
        'class', 'type', 'breakfast', 'snack', 'lunch', 'dinner'
    ];

    public function menu_food()
    {
        return $this->hasMany('App\Menu_food');
    }
}
