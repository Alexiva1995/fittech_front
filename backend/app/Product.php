<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model{
    public $table = "product";

    protected $fillable = ['ally_id', 'name','description', 'status','price'];

    public function Ally(){
        return $this->belongsTo('App\Ally');
    }
}