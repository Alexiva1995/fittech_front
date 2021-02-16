<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_plan extends Model
{
  public $table = "user_plans";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'plan_id', 'date', 'payment_method', 'payment_id', 'amount',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }

  public function plan()
  {
    $this->belongsTo('App\Plan');
  }
}
