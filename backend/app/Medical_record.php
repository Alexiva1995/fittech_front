<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Medical_record extends Model
{
  public $table = "medical_record";

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'heart_rate', 'hypertension', 'hypotension', 'lung_diseases', 'fading',
    'diabetes_insulindependent', 'chest_pains', 'cardiac_pathologies',
    'unusual_fatigue', 'renal_insufficiency', 'none',
  ];

  public function user()
  {
    $this->belongsTo('App\User');
  }
}
