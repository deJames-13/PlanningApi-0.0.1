<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarData;

class ChartController extends Controller
{
    protected $isApiResource = false;

    // BAR1 DATA CHART
    public function bar1()
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
    
}

