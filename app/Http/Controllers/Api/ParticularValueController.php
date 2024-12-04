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
    ];

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $particular = Particular::find($validated['particular_id']);
        if (!$particular) {
            return response()->json(['message' => 'Particular not found'], 404);
        }
        $particular->values()->create($validated);
        return new $this->resource($particular->values);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        $particularValue = $this->model::findOrFail($id);
        $particularValue->update($validated);
        return new $this->resource($particularValue);
    }

}
