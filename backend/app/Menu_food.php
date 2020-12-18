<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu_food extends Model
{
    public $table = "menu_foods";

    protected $fillable = ['menu_id', 'food_id', 'quantity', 'measure'];

    public function food()
    {
        return $this->belongsTo('App\Food');
    }

    public function menu()
    {
        return $this->belongsTo('App\Menu');
    }
}
