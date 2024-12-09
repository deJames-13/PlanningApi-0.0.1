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
        'annual.*.allotment' => 'required|numeric',
        'annual.*.obligated' => 'required|numeric',
        'annual.*.utilization_rate' => 'required|numeric',
        'annual.*.quarters' => 'array',
        'annual.*.quarters.*.quarter' => 'integer',
        'annual.*.quarters.*.label' => 'string',
        'annual.*.quarters.*.allotment' => 'numeric',
        'annual.*.quarters.*.obligated' => 'numeric',
        'annual.*.quarters.*.utilization_rate' => 'numeric',
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
