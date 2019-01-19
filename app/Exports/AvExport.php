<?php

namespace App\Exports;

use App\Av;

class AvExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Av::all();

    }
}