<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notfood extends Model
{
  public $table = "not_foods";

  protected $fillable = ['user_id', 'foods_id'];

  /*  public function exercise_routine(){
      $this->hasMany('App\Exercise_Routine', 'exercise_id', 'id');
    }*/
}
