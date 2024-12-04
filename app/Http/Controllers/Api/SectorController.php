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
        'name' => 'required|string|max:255',
        'short_name' => 'nullable|string|max:255',
        'full_name' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:255',
        'department_id' => 'nullable|integer|exists:departments,id',
    ];
}
