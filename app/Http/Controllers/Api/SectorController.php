<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sector;
use App\Http\Resources\SectorResource;

class SectorController extends Controller
{
    protected $model = Sector::class;
    protected $resource = SectorResource::class;
    protected $rules = [
        'name' => 'required|string|max:255|unique:sectors',
        'full_name' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'department_id' => 'nullable|integer|exists:departments,id',
    ];


    public function update(Request $request, $id)
    {
        $this->checkProperties();
        
        $rules = [
            'name' => 'required|string|max:255|unique:sectors,name,' . $id,
            'full_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'department_id' => 'nullable|integer|exists:departments,id',
        ];

        $validData = !empty($this->rules) ? $request->validate($rules) : $request->all();
        $model = $this->model::findOrFail($id);
        $model->update($validData);

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }


}
