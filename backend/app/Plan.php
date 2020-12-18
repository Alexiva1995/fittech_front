<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $table = "plans";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price', 'duration', 'description',
    ];

    public function users()
    {
        $this->belongsToMany('App\User', 'user_plans', 'plan_id', 'user_id')->withPivot('date', 'payment_method', 'payment_id', 'amount')->withTimestamp();
    }

    public function user()
    {
        $this->hasMany('App\User');
    }
}
