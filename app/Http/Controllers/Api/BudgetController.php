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
        'title' => 'required|string|unique:budgets',
        'description' => 'nullable|string',
        'current_year' => 'required|integer',
        'status' => 'nullable|string|in:draft,published',
        'current_quarter' => 'nullable|integer',
        'sector_id' => 'nullable|integer|exists:sectors,id',
        'annual' => 'nullable|array',
        'annual.*.year' => 'required|integer',
        'annual.*.allotment' => 'nullable|numeric',
        'annual.*.obligated' => 'nullable|numeric',
        'annual.*.utilization_rate' => 'nullable|numeric',
        'annual.*.quarters' => 'array',
        'annual.*.quarters.*.quarter' => 'integer',
        'annual.*.quarters.*.label' => 'nullable|string',
        'annual.*.quarters.*.allotment' => 'numeric',
        'annual.*.quarters.*.obligated' => 'numeric',
        'annual.*.quarters.*.utilization_rate' => 'numeric',
    ];
    
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['status'])){
            $validated['status'] = 'draft';
        }

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
            $budgetAnnual->quarters()->createMany($quarters);
        }

        return new $this->resource($budget);
    }

    public function update(Request $request, $id)
    {
        $rules = array_merge($this->rules, [
            'title' => 'required|string|unique:budgets,title,' . $id,
        ]);
        $validated = $request->validate($rules);

        if (!isset($validated['status']) || $request->user()->hasRole('user')) {
            $validated['status'] = 'draft';
        }


        $annualData = [];
        if ($request->has('annual')) {
            $annualData = $request->annual;
            unset($request['annual']);
        }

        $budget = $this->model::find($id);
        if (!$budget) {
            return response()->json(['message' => 'Budget not found.'], 404);
        }
        $budget->update($validated);

        $budget->annual()->forceDelete();


        foreach ($annualData as $annual) {
            $quarters = $annual['quarters'];
            unset($annual['quarters']);
            $budgetAnnual = $budget->annual()->create($annual);
            $budgetAnnual->quarters()->createMany($quarters);
        }
        
        return new $this->resource($budget);
    }

}
