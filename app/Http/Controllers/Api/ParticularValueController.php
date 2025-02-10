<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParticularValue;
use App\Models\Particular;
use App\Http\Resources\ParticularValueResource;

class ParticularValueController extends Controller
{
    
    protected $model = ParticularValue::class;
    protected $resource = ParticularValueResource::class;
    protected $rules = [
        'particular_id' => 'required|integer|exists:particulars,id',
        'year' => 'required|integer',
        'target' => 'required|numeric',
        'accomplishment' => 'required|numeric',
        'quarters' => 'nullable|array',
        'quarters.*.quarter' => 'integer',
        'quarters.*.target' => 'numeric',
        'quarters.*.accomplishment' => 'numeric',
    ];

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['values'])){
            $validated['quarters'] = [];
        }
        $quarters = $validated['quarters'];
        unset($validated['quarters']);

        $particular = Particular::find($validated['particular_id']);
        if (!$particular) {
            return response()->json(['message' => 'Particular not found'], 404);
        }
        $particular->values()->create($validated)->quarters()->createMany($quarters);
        return new $this->resource($particular->values);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['values'])){
            $validated['quarters'] = [];
        }
        $quarters = $validated['quarters'];
        unset($validated['quarters']);


        $particularValue = $this->model::findOrFail($id);
        $particularValue->update($validated);

        $particularValue->quarters()->forceDelete();
        $particularValue->quarters()->createMany($quarters);




        return new $this->resource($particularValue);
    }

}
