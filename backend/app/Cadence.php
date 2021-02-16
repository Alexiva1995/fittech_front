<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cadence extends Model
{
  public $table = "cadence";

  protected $fillable = ['objective', 'level', 'day', 'up', 'down'];

  public function Exercise_Routine()
  {
    $this->hasMany('App\Exercise_Routine', 'cadence_id', 'id');
  }
}
