<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cgi extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function canton(){
        return $this->belongsTo('App\Canton')->with('sousprefecture');
    }

    public function avs(){
        return $this->hasMany('App\Av');
    }
}
