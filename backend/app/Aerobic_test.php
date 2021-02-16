<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aerobic_test extends Model
{
  public $table = "aerobic_test";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'distance', 'result',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
