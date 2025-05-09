<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarData;
use App\Models\Budget;
use App\Models\Sector;
use App\Models\Objective;
use App\Http\Resources\BarDataResource;
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

        $bar1 = BarData::with([
            'particulars',
            'particulars.values',
            'particulars.values.quarters',

        ]);
        $bar1->where('status', 'published');
        $results = $bar1->orderBy($sort, $order)->paginate($perPage);


        return response()->json([
            'data' => BarDataResource::collection($results),
        ]);
    }

    // BUDGETS DATA CHART
    public function budgets(Request $request)
    {
        $sectorSlug = $request->sector_slug;
        $isId = $request->isId ?? false;
        if ($sectorSlug == 'none'){
            $budgets = Budget::whereNull('sector_id');
            $budgets = $budgets->where('status', 'published');
            return response()->json([
                'data' => BudgetResource::collection($budgets->get()),
            ]);
        }

        if ($isId =='true'){
            $sector = Sector::find($sectorSlug);
        }
        else {
            $sector = Sector::where('slug', $sectorSlug)->first();
        }
        
        if (!$sector) {
            return response()->json([
                'message' => "Sector with " . ($isId =='true' ? "id" : "slug") . " $sectorSlug not found.",
            ], 404);
        }
        $budgets = $sector->budgets->where('status', 'published');

        return response()->json([
            'data' => BudgetResource::collection($budgets),
        ]);

    }

    // OBJECTIVES DATA CHART
    public function objectives(Request $request)
    {
        $sectorSlug = $request->sector_slug;
        $isId = $request->isId ?? false;

        if ($sectorSlug == 'none'){
            $objectives = Objective::whereNull('sector_id');
            $objectives = $objectives->where('status', 'published');
            return response()->json([
                'data' => ObjectiveResource::collection($objectives->get()),
            ]);
        }

        if ($isId == 'true'){
            $sector = Sector::find($sectorSlug);
        }
        else {
            $sector = Sector::where('slug', $sectorSlug)->first();
        }

        if (!$sector) {
            return response()->json([
                'message' => "Sector with " . ($isId =='true' ? "id" : "slug") . " $sectorSlug not found.",
            ], 404);
        }
        $objectives = $sector->objectives->where('status', 'published');
        return response()->json([
            'data' => ObjectiveResource::collection($objectives),
        ]);
    }

    // Top Ten Sectors with High Total Accomplishment Percentage


    // Top Ten Sectors with High Total Utilzation Rate Percentage

    // 
    
}

