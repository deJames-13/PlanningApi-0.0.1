<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Http\Resources\BudgetResource;

class BudgetController extends Controller
{
    protected $model = Budget::class;
    protected $resource = BudgetResource::class;
    protected $searchableColumns = ['title', 'description', 'current_year'];
    protected $rules = [
        'title' => 'required|string',
        'description' => 'nullable|string',
        'current_year' => 'required|integer',
        'current_quarter' => 'nullable|integer',
        'sector_id' => 'nullable|integer|exists:sectors,id',
        'annual' => 'nullable|array',
        'annual.*.year' => 'required|integer',
        'annual.*.target' => 'required|numeric',
        'annual.*.accomplishment' => 'required|numeric',
        'annual.*.utilization_rate' => 'required|numeric',
        'annual.*.quarters' => 'required|array',
        'annual.*.quarters.*.quarter' => 'required|integer',
        'annual.*.quarters.*.label' => 'required|string',
        'annual.*.quarters.*.allotment' => 'required|numeric',
        'annual.*.quarters.*.obligated' => 'required|numeric',
        'annual.*.quarters.*.utilization_rate' => 'required|numeric',
    ];
    
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $annualData = [];
        if ($request->has('annual')) {
            $annualData = $request->annual;
            unset($request['annual']);
        }

        $budget = $this->model::create($validated);

        foreach ($annualData as $annual) {
            $quarters = $annual['quarters'];
            unset($annual['quarters']);
            $budgetAnnual = $budget->annual()->create($annual);
            $budgetAnnual->quarter()->createMany($quarters);
        }

        return new $this->resource($budget);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        $annualData = [];
        if ($request->has('annual')) {
            $annual = $request->annual;
            unset($request['annual']);
        }

        $model = $this->model::findOrFail($id);
        $budget = $model->update($request->all());

        $budget->annual()->delete();
        foreach ($annualData as $annual) {
            $quarters = $annual['quarters'];
            unset($annual['quarters']);
            $budgetAnnual = $budget->annual()->create($annual);
            $budgetAnnual->quarter()->createMany($quarters);
        }
        

        return new $this->resource($budget);
    }

}
