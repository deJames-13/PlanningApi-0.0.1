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
    ];
    protected $with = ['particulars'];

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $particulars = $validated['particulars'];
        unset($validated['particulars']);

        $barData = $this->model::create($validated);

        foreach ($particulars as $particular) {
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

        $barData->particulars()->forceDelete();

        foreach ($particulars as $particular) {
            $values = $particular['values'];
            unset($particular['values']);

            $particular = $barData->particulars()->create($particular);

            foreach ($values as $value) {
                $quarters = $value['quarters'];
                unset($value['quarters']);

                $particular->values()->create($value);
                $newValue = $particular->values()->create($value);
                $newValue->quarters()->createMany($quarters);

            }
        }
        return new $this->resource($barData);

    }

}
