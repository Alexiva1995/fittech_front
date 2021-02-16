<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routine_Home extends Model
{
    public $table = "routine_home";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'test_home_id', 'day', 'ratio_w', 'ratio_r', 'ready', 'calf'
    ];

    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function exercises_home()
    {
        $this->hasMany('App\Exercise_Home_Routine', 'routine_home_id');
    }
}
