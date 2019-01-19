<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canton extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function sousprefecture(){
        return $this->belongsTo('App\Sousprefecture')->with('prefecture');
    }

    public function cgis(){
        return $this->hasMany('App\Cgi');
    }
}
