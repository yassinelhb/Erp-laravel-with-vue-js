<?php

namespace App\Exports;

use App\Saison;

class SaisonsExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

   public function collection()
   {
       return  Saison::all();

   }
}