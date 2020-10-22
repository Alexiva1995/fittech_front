<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise_Routine extends Model
{
  public $table = "exercise_routine";

  protected $fillable = ['user_id', 'routine_id', 'exercise_id', 'stage', 'cadence_id'];

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

  public function cadence()
  {
    $this->belongsTo('App\Cadence', 'cadence_id', 'id');
  }
}
