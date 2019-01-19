<?php

namespace App\Exports;

use App\Article;

class ArticlesExport implements \Maatwebsite\Excel\Concerns\FromCollection
{

    public function collection()
    {
        return  Article::all();

    }
}