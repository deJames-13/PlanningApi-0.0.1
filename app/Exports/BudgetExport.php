<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Budget;
use App\Models\BarData;

class BudgetExport implements FromView
{
    public function view(): View
    {
        $data = Budget::with([
            'annual' => function ($query) {
                $query->orderBy('year', 'asc');
            },
            'annual.quarters',
        ]);

        $data = $data->get();
        return view('exports.budgets', ['data' => $data]
        );
    }
}
