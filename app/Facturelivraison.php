<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facturelivraison extends Model
{
    public function bordereaulivraison(){
       return $this->belongsTo('App\Bordereaulivraison')->with('besoin');
    }
    public function reglements(){
        return $this->hasMany('App\Bordereaureglement');
    }
    public function factureglobal()
    {
        return $this->hasOne('App\Factureglobal');
    }

}
