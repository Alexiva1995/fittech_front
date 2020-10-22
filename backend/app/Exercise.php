<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
  public $table = "exercise";

  protected $fillable = ['cod', 'name', 'url', 'pro', 'side'];

  public function exercise_routine()
  {
    $this->hasMany('App\Exercise_Routine', 'exercise_id', 'id');
  }
}
