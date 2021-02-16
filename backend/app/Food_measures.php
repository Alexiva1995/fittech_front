<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food_measures extends Model
{
    public $table = "food_measures";

    protected $fillable = ['user_id', 'total_carbo', 'total_protein', 'total_greases', 'tmb', 'tmba', 'strategy_n'];
}
