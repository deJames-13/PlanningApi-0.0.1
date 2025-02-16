<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sector;
use App\Http\Resources\SectorResource;
use App\Exports\SectorExport;

class SectorController extends Controller
{
    protected $model = Sector::class;
    protected $resource = SectorResource::class;
    protected $ExportClass = SectorExport::class;

    protected $with = [
        'department',
        'objectives',
        'budgets',
    ];
    
    protected $searchableColumns = [
        'name', 
        'full_name', 
        'description', 
        'department.name',
        'department.slug',
        'department.id',
    ];
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
    
    public function sectorList(){
        $sectors = Sector::all();
        return response()->json($sectors);
    }

    public function export(string $id, string $type){
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid id. Please select only one sector.'], 422);
        } 
        return parent::export($id, $type);
    }

}
