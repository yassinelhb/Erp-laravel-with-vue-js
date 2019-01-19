<?php

namespace App\Exports;

use App\Prefecture;

class PrefectureExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Prefecture::all();

    }
}