<?php

namespace App\Exports;


use App\Saison;
use App\User;

class UsersExport implements \Maatwebsite\Excel\Concerns\FromCollection
{
   public function collection()
   {
       return User::select('nom','matricule','email','role')->where('role' ,'!=','Administrateur')->get();

   }
}