<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bordereaureglement extends Model
{
    public function facturelivraison(){
        return $this->belongsTo('App\Facturelivraison')->with('bordereaulivraison','factureglobal');
    }
}
