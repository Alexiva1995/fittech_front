<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class measurement_record extends Model
{
  public $table = "measurement_record";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id', 'min_waist', 'max_waist', 'hip', 'neck', 'right_thigh', 'left_thigh',   'right_arm', 'left_arm', 'left_arm_flexed',
    'right_arm_flexed', 'right_calf', 'left_calf', 'torax', 'waist_hip', 'front_photo', 'profile_photo', 'back_photo', 'weight', 'stature'
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
