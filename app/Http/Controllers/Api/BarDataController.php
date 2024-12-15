<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarData;
use App\Http\Resources\BarDataResource;

class BarDataController extends Controller
{
    
    protected $model = BarData::class;
    protected $resource = BarDataResource::class;
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'nullable|string|in:draft,published',
        'particulars' => 'required|array',
        'particulars.*.title' => 'required|string|max:255',
        'particulars.*.description' => 'nullable|string',
        'particulars.*.values' => 'required|array',
        'particulars.*.values.*.year' => 'required|integer',
        'particulars.*.values.*.target' => 'required|numeric',
        'particulars.*.values.*.accomplishment' => 'required|numeric',
    ];
    protected $searchableColumns = [
        'title',
        'description',
    ];

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

        $model->load('particular');

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $particulars = $validated['particulars'];
        unset($validated['particulars']);

        $barData = $this->model::create($validated);

        foreach ($particulars as $particular) {
            $values = $particular['values'];
            unset($particular['values']);

            $particular = $barData->particular()->create($particular);

            foreach ($values as $value) {
                $particular->values()->create($value);
            }
        }
        return new $this->resource($barData);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['status']) || $request->user()->hasRole('user')) {
            $validated['status'] = 'draft';
        }
        $particulars = $validated['particulars'];
        unset($validated['particulars']);

        $barData = $this->model::findOrFail($id);
        $barData->update($validated);

        $barData->particular()->forceDelete();

        foreach ($particulars as $particular) {
            $values = $particular['values'];
            unset($particular['values']);

            $particular = $barData->particular()->create($particular);

            foreach ($values as $value) {
                $particular->values()->create($value);
            }
        }
        return new $this->resource($barData);

    }

}
