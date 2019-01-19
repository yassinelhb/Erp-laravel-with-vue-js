<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sousprefecture extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function prefecture(){
        return $this->belongsTo('App\Prefecture');
    }

    public function cantons(){
        return $this->hasMany('App\Canton');
    }
}
