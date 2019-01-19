<?php

namespace App\Exports;

use App\Sousprefecture;

class SousPrefectureExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Sousprefecture::all();

    }
}