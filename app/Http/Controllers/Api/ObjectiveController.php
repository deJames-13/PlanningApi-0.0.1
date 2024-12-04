<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Objective;
use App\Http\Resources\ObjectiveResource;

class ObjectiveController extends Controller
{
    protected $model = Objective::class;
    protected $resource = ObjectiveResource::class;
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'sector_id' => 'required|exists:sectors,id',
        'quarters' => 'required|array',
        'quarters.*.quarter' => 'required|integer|between:1,4',
        'quarters.*.label' => 'required|string|max:255',
        'quarters.*.target' => 'required|numeric',
        'quarters.*.accomplishment' => 'required|numeric',
        'quarters.*.utilization_rate' => 'required|numeric',
    ];


    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $quarters = $validated['quarters'];
        unset($validated['quarters']);

        $objective = $this->model::create($validated);
        $objective->quarter()->createMany($quarters);

        return new $this->resource($objective);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        $quarters = $validated['quarters'];
        unset($validated['quarters']);

        $objective = $this->model::findOrFail($id);
        $objective->update($validated);
        $objective->quarter()->delete();
        $objective->quarter()->createMany($quarters);

        return new $this->resource($objective);
    }

}
