<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarData;
use App\Http\Resources\BarDataResource;
use App\Exports\BarDataExport;

class BarDataController extends Controller
{
    
    protected $model = BarData::class;
    protected $resource = BarDataResource::class;
    protected $ExportClass = BarDataExport::class;


    protected $rules = [
        'title' => 'required|string|max:255|unique:bar_datas,title',
        'description' => 'nullable|string',
        'status' => 'nullable|string|in:draft,published',
        'particulars' => 'required|array',
        'particulars.*.title' => 'required|string|max:255',
        'particulars.*.description' => 'nullable|string',
        'particulars.*.values' => 'nullable|array',
        'particulars.*.values.*.year' => 'nullable|integer',
        'particulars.*.values.*.target' => 'nullable|numeric',
        'particulars.*.values.*.accomplishment' => 'nullable|numeric',
        'particulars.*.values.*.quarters' => 'nullable|array',
        'particulars.*.values.*.quarters.*.quarter' => 'nullable|integer',
        'particulars.*.values.*.quarters.*.target' => 'nullable|numeric',
        'particulars.*.values.*.quarters.*.accomplishment' => 'nullable|numeric',
        

    ];
    protected $searchableColumns = [
        'title',
        'description',
        'status',
    ];
    protected $with = [
        'particulars',
        'particulars.values',
        'particulars.values.quarters',
    ];

    public function byYear($year)
    {
        $this->checkProperties(2);
        
        return response()->json([
            'data' => $year,
        ], 200);
    }

    public function index(Request $request)
    {
        return parent::index($request);
    }

    public function show($id)
    {
        $this->checkProperties(2);
        $isSlug = request()->is_slug ?? false;
        try {
            $model = $isSlug ? $this->model::where('slug', $id)->firstOrFail() : $this->model::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Record not found',
                'isSlug' => $isSlug,
            ], 404);
        }

        $model->load([
            'particulars',
            'particulars.values',
            'particulars.values.quarters',
        ]);
        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['status'])){
            $validated['status'] = 'draft';
        }
        $particulars = $validated['particulars'];
        unset($validated['particulars']);

        $barData = $this->model::create($validated);

        foreach ($particulars as $particular) {
            if (!isset($particular['values'])){
                $particular['values'] = [];
            }
            $values = $particular['values'];
            unset($particular['values']);

            $particular = $barData->particulars()->create($particular);

            foreach ($values as $value) {
                $quarters = $value['quarters'];
                unset($value['quarters']);

                $newValue = $particular->values()->create($value);
                $newValue->quarters()->createMany($quarters);
            }


        }
        return new $this->resource($barData);
    }

    public function update(Request $request, $id)
    {
        $rules = array_merge(
            $this->rules,
            ['title' => 'required|string|max:255|unique:bar_datas,title,' . $id]
        );
        $validated = $request->validate($rules);
        if (!isset($validated['status']) || $request->user()->hasRole('user')) {
            $validated['status'] = 'draft';
        }
        $particulars = $validated['particulars'];
        unset($validated['particulars']);

        $barData = $this->model::findOrFail($id);
        $barData->update($validated);

        $barData->particulars->each(function ($particular) {
            $particular->values()->forceDelete();
        });
        $barData->particulars()->forceDelete();


        foreach ($particulars as $particular) {
            if (!isset($particular['values'])){
                $particular['values'] = [];
            }
            $values = $particular['values'];
            unset($particular['values']);

            $particular = $barData->particulars()->create($particular);

            foreach ($values as $value) {
                if (!isset($value['quarters'])){
                    $value['quarters'] = [];
                }
                $quarters = $value['quarters'];
                unset($value['quarters']);

                $newValue = $particular->values()->create($value);
                $newValue->quarters()->createMany($quarters);

            }
        }
        return new $this->resource($barData);

    }

    public function deleteAllValuesWithYear(string $year, string $id)
    {
        $barData = $this->model::where('id', $id)
            ->whereHas('particulars.values', function ($query) use ($year) {
                $query->where('year', $year);
            })->firstOrFail();
    
        $barData->particulars->each(function ($particular) use ($year) {
            $particular->values()->where('year', $year)->delete();
        });
    
        return response()->json([
            'message' => "Values with year $year deleted successfully",
        ], 200);
    }

    public function deleteAllByStatus(string $status)
    {
        $barDatas = $this->model::where('status', $status)->get();
        $barDatas->each(function ($barData) {
            $barData->particulars->each(function ($particular) {
                $particular->values()->delete();
            });
            $barData->particulars()->delete();
        });
        $barDatas->each->delete();
    
        return response()->json([
            'message' => 'BarDatas deleted successfully',
        ], 200);
    }

    public function publishAllDrafts()
    {
        $barDatas = $this->model::where('status', 'draft')->get();
        $barDatas->each(function ($barData) {
            $barData->update(['status' => 'published']);
        });
    
        return response()->json([
            'message' => 'BarDatas published successfully',
        ], 200);
    }


}
