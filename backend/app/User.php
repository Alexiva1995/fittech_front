<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password', 'gender', 'age', 'weight', 'stature', 'objective',
    'act_physical', 'training_experience', 'training_place', 'heart_rate',
    'imc', 'ica', 'fcmax', 'fcmin', 'risk', 'obesity_cc', 'indicator_imc', 'act', 'feeding_type', 'avatar',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function Medical_record()
  {
    $this->hasOne('App\Medical_record');
  }

  public function plan()
  {
    $this->belongsTo('App\Plan');
  }

  public function User_plan()
  {
    $this->hasMany('App\User_plan');
  }

  public function Family_medical_record()
  {
    $this->hasOne('App\Family_medical_record');
  }

  public function measurement_record()
  {
    $this->hasMany('App\measurement_record');
  }

  public function Aerobic_test()
  {
    $this->hasMany('App\Aerobic_test');
  }

  public function Power_test()
  {
    $this->hasMany('App\Power_test');
  }

  public function Routine()
  {
    $this->hasMany('App\Routine');
  }

  public function Home_test()
  {
    $this->hasMany('App\Home_test');
  }

  public function grease_record()
  {
    $this->hasMany('App\grease_record');
  }

  public function users()
  {
    return $this->belongsToMany('App\User', 'user_plans', 'user_id', 'plan_id')->withPivot('date', 'payment_method', 'payment_id', 'amount')->withTimestamp();
  }
}
