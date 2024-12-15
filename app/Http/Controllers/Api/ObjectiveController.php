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
        'status' => 'nullable|string|in:draft,published',
        'quarters' => 'nullable|array',
        'quarters.*.quarter' => 'nullable|integer|between:1,4',
        'quarters.*.label' => 'nullable|string|max:255',
        'quarters.*.target' => 'nullable|numeric',
        'quarters.*.accomplishment' => 'nullable|numeric',
        'quarters.*.utilization_rate' => 'nullable|numeric',
    ];


    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $quarterData = [];
        if (isset($validated['quarters'])) {
            $quarterData = $validated['quarters'];
            unset($validated['quarters']);
        }

        $objective = $this->model::create($validated);
        $objective->quarter()->createMany($quarterData);

        return new $this->resource($objective);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate($this->rules);
        if (!isset($validated['status']) || $request->user()->hasRole('user')) {
            $validated['status'] = 'draft';
        }
        $quarterData = [];
        if (isset($validated['quarters'])) {
            $quarterData = $validated['quarters'];
            unset($validated['quarters']);
        }

        $objective = $this->model::findOrFail($id);
        $objective->update($validated);
        
        $objective->quarter()->forceDelete();
        $objective->quarter()->createMany($quarterData);

        return new $this->resource($objective);
    }

}
