<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bordereaulivraison extends Model
{
    public function besoin(){
        return $this->belongsTo('App\Besoin')->with('articles','av','bordereaux');
    }
    public function factures(){
        return $this->hasMany('App\Facturelivraison');
    }
}
