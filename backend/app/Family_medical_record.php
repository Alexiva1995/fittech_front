<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family_medical_record extends Model
{
  public $table = "family_medical_record";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'cardiac_arrhythmia', 'heart_attack', 'heart_operation', 'congenital_heart_disease',  'early_death', 'high_blood_pressure', 'diabetes', 'user_id', 'none',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
