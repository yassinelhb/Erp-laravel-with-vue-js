<?php

namespace App\Exports;

use App\Canton;

class CantonExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Canton::all();

    }
}