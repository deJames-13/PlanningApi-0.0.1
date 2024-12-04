<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Particular;
use App\Models\BarData;
use App\Http\Resources\ParticularResource;

class ParticularController extends Controller
{
    
    protected $model = Particular::class;
    protected $resource = ParticularResource::class;
    protected $rules = [
        'bar_data_id' => 'required|integer|exists:bar_datas,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|string',
        'values' => 'required|array',
        'values.*.year' => 'required|integer',
        'values.*.target' => 'required|numeric',
        'values.*.accomplishment' => 'required|numeric',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $values = $validated['values'];
        unset($validated['values']);

        $barData = BarData::find($validated['bar_data_id']);
        if (!$barData) {
            return response()->json(['message' => 'Bar data not found'], 404);
        }

        $barData->particular()->create($validated)->values()->createMany($values);

        return new $this->resource($barData->particular);
    
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        $values = $validated['values'];
        unset($validated['values']);

        $particular = $this->model::findOrFail($id);
        $particular->update($validated);

        $particular->values()->delete();
        $particular->values()->createMany($values);
        

        return new $this->resource($particular);   
    }

}
