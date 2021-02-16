<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class grease_record extends Model
{
  public $table = "grease_record";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'grease',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
