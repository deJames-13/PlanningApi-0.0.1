<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BudgetAnnual;
use App\Models\Budget;
use App\Http\Resources\BudgetAnnualResource;

class BudgetAnnualController extends Controller
{
    protected $model = BudgetAnnual::class;
    protected $resource = BudgetAnnualResource::class;
    protected $rules = [
        'budget_id' => 'required|integer|exists:budgets,id',
        'year' => 'required|integer',
        'target' => 'required|numeric',
        'accomplishment' => 'required|numeric',
        'utilization_rate' => 'required|numeric',
        'quarters' => 'required|array',
        'quarters.*.quarter' => 'required|integer',
        'quarters.*.label' => 'required|string',
        'quarters.*.allotment' => 'required|numeric',
        'quarters.*.obligated' => 'required|numeric',
        'quarters.*.utilization_rate' => 'required|numeric',
    ];
    
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $quarters = $validated['quarters'];
        unset($validated['quarters']);

        $budget = Budget::find($validated['budget_id']);
        if (!$budget) {
            return response()->json(['message' => 'Budget not found'], 404);
        }

        $budget->annual()->create($validated)->quarter()->createMany($quarters);

        return new $this->resource($budget->annual);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        $quarters = $validated['quarters'];
        unset($validated['quarters']);

        $budgetAnnual = $this->model::findOrFail($id);
        $budgetAnnual->update($validated);

        $budgetAnnual->quarter()->delete();
        $budgetAnnual->quarter()->createMany($quarters);

        return new $this->resource($budgetAnnual);
    }

}
