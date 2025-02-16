<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Objective;

class ObjectiveExport implements FromView
{
    public function __construct(string $id)
    {
        $this->id = $id === 'all' ? null : $id;
    }
    public function view(): View
    {   
        $data = Objective::with([
            'sector',
            'quarters'
        ]);
        $data = $data->get();
        return view('exports.objectives', ['data' => $data]
        );
    }
}
