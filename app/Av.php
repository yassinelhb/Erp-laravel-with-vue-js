<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Av extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function cgi(){
        return $this->belongsTo('App\Cgi')->with('canton');
    }
    public function besoins(){
        return $this->hasMany('App\Besoin');
    }

    public function tickets(){
        return $this->hasMany('App\Ticket');
    }
}
