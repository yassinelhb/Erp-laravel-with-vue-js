<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detailbesoin extends Model
{
    protected $table = 'article_besoin';

    public function article(){
        return $this->belongsTo('App\Article')->with('saisons');
    }

    public function besoin(){
        return $this->belongsTo('App\Besoin');
    }
}
