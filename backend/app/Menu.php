<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = "menu";

    protected $fillable = [
        'user_id', 'type_food', 'total_proteins', 'total_greases', 'total_carbos', 'total_calories',
        'day'
    ];

    public function menu_food()
    {
        return $this->hasMany('App\Menu_food');
    }
}
