<?php

namespace App\Exports;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Sector;

class SectorExport implements FromView
{
    public function __construct(string $id)
    {
        $this->id = $id === 'all' ? null : $id;
    }


    public function view(): View
    {
        
        $data = Sector::with([
            'department',
            'budgets',
            'budgets.annual',
            'budgets.annual.quarters',
            'objectives',
            'objectives.quarters',
        ]);

        if ($this->id) {
            $data = $data->where('id', $this->id);
        }

        $data = $data->get();
        return view('exports.sectors', [
            'data' => $data,
        ]);
    }

}