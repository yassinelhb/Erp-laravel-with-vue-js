<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Besoin extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function av(){
        return $this->belongsTo('App\Av')->with('cgi');
    }

    public function saison(){
        return $this->belongsTo('App\Saison');
    }

    public function articles(){

        return $this->belongsToMany('App\Article')->withPivot('quantite');
    }
    public function bordereaux(){
        return $this->hasMany('App\Bordereaulivraison');
    }
}
