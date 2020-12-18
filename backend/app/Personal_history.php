<?php

namespace App;

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal_history extends Model
{
    public $table = "personal_history";

    protected $fillable = ['user_id', 'description'];
}
