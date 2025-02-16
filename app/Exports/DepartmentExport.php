<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\Department;
use App\Models\Sector;

class DepartmentExport implements FromView
{
    public function view(): View
    {   
        $noDepartment = Sector::where('department_id', null)->get();
        $data = Department::with([
            'sectors'
        ]);
        $data = $data->get();
        $data = $data->toArray();
        $data = array_merge($noDepartment->toArray(), $data);
        return view('exports.departments', ['data' => $data]
        );
    }
}
