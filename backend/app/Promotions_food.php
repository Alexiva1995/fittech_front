<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotions_food extends Model
{
    public $table = "promotions_food";

    protected $fillable = ['user_id', 'type_food', 'protein', 'greases', 'carbo'];
}
