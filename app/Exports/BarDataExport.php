<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\BarData;

class BarDataExport implements FromView
{
    public function view(): View
    {
        return view('exports.example', [
            'data' => [
                [
                    'title' => 'Title 1',
                    'description' => 'Description 1',
                ],
                [
                    'title' => 'Title 2',
                    'description' => 'Description 2',
                ],
                [
                    'title' => 'Title 3',
                    'description' => 'Description 3',
                ],
            ]
        ]);
    }

}
