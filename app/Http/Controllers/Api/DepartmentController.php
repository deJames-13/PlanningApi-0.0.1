<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    protected $model = Department::class;
    protected $resource = DepartmentResource::class;
    protected $rules = [
        'name' => 'required|string|max:255',
        'short_name' => 'nullable|string|max:255',
        'full_name' => 'nullable|string|max:255',
    ];

}
