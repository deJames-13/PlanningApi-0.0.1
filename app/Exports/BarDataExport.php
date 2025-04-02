<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\BarData;

class BarDataExport implements FromView
{
    public function __construct(string $id)
    {


        $this->id = $id === 'all' ? null : $id;
        if (strpos($id, ',') !== false) {
            $this->id = explode(',', $id);
        } 
        else if (is_numeric($id)) {
            $this->id = [$id];
        }
    }
    public function view(): View
    {
        $data = BarData::with([
            'particulars',
            'particulars.values',
            'particulars.values.quarters',
        ]);
        if ($this->id) {
            $data = $data->whereIn('id', $this->id);
        }
        $data = $data->get();
        return view('exports.bar-data', [
            'data' => $data
            ]
        );
    }

}
