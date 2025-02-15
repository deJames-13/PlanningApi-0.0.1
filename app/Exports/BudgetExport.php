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
        return view('exports.budgets', [
            'data' => Budget::with([
                'annual',
                'annual.quarters',
            ])->get()
            ]
        );
    }
}
