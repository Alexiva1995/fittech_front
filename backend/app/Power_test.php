<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class power_test extends Model
{
  public $table = "power_test";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'exercise', 'result_75', 'level', 'weight', 'repetitions', 'repose', 'exercise_2', 'result_75_2', 'level_2', 'weight_2', 'repetitions_2', 'repose_2', 'exercise_3', 'result_75_3', 'level_3', 'weight_3', 'repetitions_3', 'repose_3',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
