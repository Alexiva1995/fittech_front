<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ally extends Model{
    public $table = "allies";

    protected $fillable = ['name'];

    public function Product(){
        return $this->hasMany('App\Product');
    }
}