<?php

namespace App\Exports;

use App\Cgi;

class CgiExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Cgi::all();

    }
}