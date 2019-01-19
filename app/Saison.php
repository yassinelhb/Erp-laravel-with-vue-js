<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saison extends Model
{
    public function articles(){

        return $this->belongsToMany('App\Article');
    }

    public function besoins(){
        return $this->hasMany('App\Besoin')->with('articles');
    }

    public function tickets(){
        return $this->hasMany('App\Ticket');
    }
}
