<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prefecture extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function sousprefectures(){
        return $this->hasMany('App\Sousprefecture');
    }
}
