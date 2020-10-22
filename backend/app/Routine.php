<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    public $table = "routine";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'power_test_id', 'day', 'ready', 'calf'
    ];

    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function exercises()
    {
        $this->hasMany('App\Exercise_Routine', 'routine_id');
    }
}
