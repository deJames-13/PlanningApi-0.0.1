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
        'index' => 'nullable|integer',
        'related_to' => 'nullable|integer|exists:departments,id',
    ];

    
    public function update(Request $request, $id)
    {
        $this->checkProperties();
        
        $rules = [
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'full_name' => 'nullable|string|max:255',
            'index' => 'nullable|integer',
            'related_to' => 'nullable|integer|exists:departments,id|not_in:' . $id,
        ];

        $validData = !empty($this->rules) ? $request->validate($rules) : $request->all();
        $model = $this->model::findOrFail($id);
        $model->update($validData);

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }

    public function departmentNavList(Request $request)
    {
        // Get sectors that aren't attached to departments, ordered by index
        $sectors = Sector::where('department_id', null)->orderBy('index', 'asc')->get();
        
        // Get parent departments (where related_to is null), ordered by index
        $parentDepartments = Department::whereNull('related_to')
            ->with(['sectors' => function($query) {
                $query->orderBy('index', 'asc');
            }])
            ->orderBy('index', 'asc')
            ->get();
            
        // Get child departments (where related_to is not null), ordered by index
        $childDepartments = Department::whereNotNull('related_to')
            ->with(['sectors' => function($query) {
                $query->orderBy('index', 'asc');
            }])
            ->orderBy('index', 'asc')
            ->get();
            
        // Group child departments by their parent id for easier organization
        $childrenByParent = [];
        foreach ($childDepartments as $child) {
            if (!isset($childrenByParent[$child->related_to])) {
                $childrenByParent[$child->related_to] = [];
            }
            $childrenByParent[$child->related_to][] = $child;
        }
        
        // Add children to their parents
        foreach ($parentDepartments as $department) {
            $department->children = $childrenByParent[$department->id] ?? [];
        }
        
        // Prepare the response data
        $departmentsCollection = $this->resource::collection($parentDepartments);
        
        return response()->json([
            'data' => array_merge($sectors->toArray(), $departmentsCollection->toArray($request))
        ], 200);
    }
}
