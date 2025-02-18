<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Http\Resources\BudgetResource;
use App\Exports\BudgetExport;



class BudgetController extends Controller
{
    protected $model = Budget::class;
    protected $resource = BudgetResource::class;
    protected $ExportClass = BudgetExport::class;

    protected $with = ['sector'];
    
    protected $searchableColumns = [
        'title', 
        'description', 
        'current_year',
        'current_quarter',
        'status',
        'sector.name',
        'sector.slug',
    ];
    protected $rules = [
        'title' => 'required|string|unique:budgets',
        'description' => 'nullable|string',
        'status' => 'nullable|string|in:draft,published',
        'current_year' => 'nullable|integer',
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
        if (!isset($validated['current_year'])){
            $validated['current_year'] = end($annualData)['year'] ?? date('Y');
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
        if (!isset($validated['current_year'])){
            $validated['current_year'] = end($annualData)['year'] ?? date('Y');
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


    public function deleteAnnual(string $year){

        $this->checkProperties(2);
        
        $budgets = Budget::whereHas('annual', function ($query) use ($year) {
            $query->where('year', $year);
        })->get();

        foreach ($budgets as $budget) {
            $budget->annual()->where('year', $year)->delete();
        }

        return response()->json([
            'message' => 'Annual budget for year ' . $year . ' has been deleted.'
        ]);
    }
    public function restoreAnnual(string $year){

        $this->checkProperties(2);

        $budgets = Budget::whereHas('annual', function ($query) use ($year) {
            $query->where('year', $year);
        })->onlyTrashed()->get();

        foreach ($budgets as $budget) {
            $budget->annual()->where('year', $year)->restore();
        }

        return response()->json([
            'message' => 'Annual budget for year ' . $year . ' has been restored.'
        ]);
    }
    public function deleteAllByStatus(string $status)
    {
        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        $barDatas = $this->model::where('status', $status)->get();

        $barDatas->each(function ($barData) use ($isAdmin) {
            if (!$isAdmin) {
                $barData->update(['status' => 'pending delete']);
            } else {
                $barData->delete();
            }
        });

        return response()->json([
            'message' => 'BarDatas deleted successfully',
        ], 200);
    }
    public function restoreAllByStatus(string $status)
    {
        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        $barDatas = $this->model::where('status', $status)->get();

        $barDatas->each(function ($barData) use ($isAdmin) {
            if (!$isAdmin) {
                $barData->update(['status' => 'pending restore']);
            } else {
                $barData->restore();
            }
        });

        return response()->json([
            'message' => 'BarDatas deleted successfully',
        ], 200);
    }

    public function export(string $id, string $type){
        if (!$this->isNumericArray($id) && $id !== 'all') {
            return response()->json(['message' => 'Invalid ids. None numeric value detected.'], 422);
        } 
        return parent::export($id, $type);
    }


}
