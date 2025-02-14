<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\BarData;

class BarDataExport implements FromView
{
    public function view(): View
    {
        return view('exports.bar-data', [
            'data' => BarData::with([
                'particulars',
                'particulars.values',
                'particulars.values.quarters',
            ])->get()
            ]
        );
    }

}
