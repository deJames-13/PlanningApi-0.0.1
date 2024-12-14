<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarData;
use App\Models\Budget;
use App\Models\Sector;
use App\Http\Resources\SectorResource;
use App\Http\Resources\BudgetResource;
use App\Http\Resources\ObjectiveResource;

class ChartController extends Controller
{
    protected $isApiResource = false;

    // BAR1 DATA CHART
    public function bar1(Request $request)
    {
        $perPage = $request->per_page ?? 5;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';

        $bar1 = BarData::query();

        
        $results = $bar1->orderBy($sort, $order)->paginate($perPage);
            return response()->json([
                ...$results->toArray(),
                'data' => $results->getCollection()->map(function ($item) {
                    return $item->mapItem();
                }),
        ]);
    }

    // BUDGETS DATA CHART
    public function budgets(Request $request)
    {
        $sectorSlug = $request->sector_slug;
        if ($sectorSlug == 'none'){
            $budgets = Budget::whereNull('sector_id')->get();
            return response()->json([
                'data' => BudgetResource::collection($budgets),
            ]);
        }
        
        $sector = Sector::where('slug', $sectorSlug)->first();
        if (!$sector) {
            return response()->json([
                'message' => 'Sector not found',
            ], 404);
        }

        return response()->json([
            'data' => BudgetResource::collection($sector->budgets),
        ]);

    }

    // OBJECTIVES DATA CHART
    public function objectives(Request $request)
    {
        $sectorSlug = $request->sector_slug;
        $sector = Sector::where('slug', $sectorSlug)->first();
        if (!$sector) {
            return response()->json([
                'message' => 'Sector not found',
            ], 404);
        }
        return response()->json([
            'data' => ObjectiveResource::collection($sector->objectives),
        ]);
    }
    
}

