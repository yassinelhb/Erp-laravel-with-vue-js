<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function saison(){
        return $this->belongsTo('App\Saison');
    }
    public function av(){
        return $this->belongsTo('App\Av');
    }

}
