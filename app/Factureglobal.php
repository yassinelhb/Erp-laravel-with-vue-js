<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factureglobal extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function facturelivraison(){
        return $this->belongsTo('App\facturelivraison')->with('bordereaulivraison','reglements');
    }
}
