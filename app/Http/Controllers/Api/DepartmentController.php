<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Sector;
use App\Exports\DepartmentExport;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    protected $model = Department::class;
    protected $resource = DepartmentResource::class;
    protected $ExportClass = DepartmentExport::class;
    protected $rules = [
        'name' => 'required|string|max:255|unique:departments',
        'full_name' => 'nullable|string|max:255',
    ];

    
    public function update(Request $request, $id)
    {
        $this->checkProperties();
        
        $rules = [
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'full_name' => 'nullable|string|max:255',
        ];

        $validData = !empty($this->rules) ? $request->validate($rules) : $request->all();
        $model = $this->model::findOrFail($id);
        $model->update($validData);

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }

    public function departmentNavList(Request $request)
    {
        $sectors = Sector::where('department_id', null)->get();
        $departments = Department::with('sectors')->get();
        $departmentsWithSectors = $this->resource::collection($departments);
        
        return response()->json([
            'data' => array_merge($sectors->toArray(), $departmentsWithSectors->toArray($request))
        ], 200);
    }


}
