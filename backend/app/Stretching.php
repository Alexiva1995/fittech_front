<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stretching extends Model
{
    public $table = "stretching";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'duration', 'side', 'link'
    ];
}
