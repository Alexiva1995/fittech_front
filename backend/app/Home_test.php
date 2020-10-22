<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Home_test extends Model
{
  public $table = "home_test";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'level', 'result',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
