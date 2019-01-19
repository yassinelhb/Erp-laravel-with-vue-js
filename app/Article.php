<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    public function saisons()
    {
       return $this->belongsToMany('App\Saison')->withPivot('id','prixvente','tva');
    }

    public function besoins()
    {
        return $this->belongsToMany('App\Besoin');
    }
}
