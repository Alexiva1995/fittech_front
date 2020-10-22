<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise_Home_Routine extends Model
{
  public $table = "exercise_home_routine";

  protected $fillable = ['user_id', 'routine_home_id', 'exercise_id', 'stage', 'repetitions'];

  public function user()
  {
    $this->belongsTo('App\User');
  }

  public function routine()
  {
    $this->belongsTo('App\Routine', 'routine_id');
  }

  public function exercise()
  {
    $this->belongsTo('App\Exercise', 'exercise_id', 'id');
  }
}
