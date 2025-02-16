<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Maatwebsite\Excel\Facades\Excel;
// General Controller class
abstract class Controller
{
    protected $model = null;
    protected $resource = null;
    protected $ExportClass = null;
    protected $isApiResource = true;

    protected $rules = [];
    protected $messages = [];
    protected $searchableColumns = [];
    protected $with = [];
    
    protected $orderBy = 'id';
    protected $order = 'asc';
    protected $perPage = 10;

    public function isNumericArray($array)
    {

        if (is_null($array)) {
            return false;
        }
        if (strpos($array, ',') !== false) {
            $array = explode(',', $array);
        } 
        else if (is_numeric($array)) {
            $array = [$array];
        } 
        else {
            return false;
        }

        return count(array_filter(array_keys($array), 'is_numeric')) == count($array);
    }

    public function checkProperties($level = 3)
    {
        if (!$this->isApiResource && $level > 0) {
            throw new \Exception('Invalid request.');
        }

        if (is_null($this->model) && $level > 0) {
            throw new \Exception('Model is not set.');
        }

        if (is_null($this->resource) && $level > 1) {
            throw new \Exception('Resource is not set.');
        }

        if (empty($this->rules) && $level > 2) {
            \Log::warning('Rules are not set.');
        }
    }

    public function index(Request $request)
    {
        $this->checkProperties(2);

        $perPage = $request->per_page ?? $this->perPage;
        $sort = $request->sort ?? $this->orderBy;
        $order = $request->order ?? $this->order;
        $search = $request->search ?? '';
        if ($search !== '') {
            $request->merge(['page' => 1]);
        }

        $with = $request->with ?? false;
        $this->with = empty($with) ? $this->with : explode(',', $with);
        if ($with === 'none'){
            $this->with = [];
        }
        if (!empty($this->with) || !$with == 'none' || !$with == false) {
            $query = $this->model::with($this->with);
        } else {
            $query = $this->model::query();
        }
        
        
        if ($perPage == 'all') {
            return response()->json([
                'data' => $query->get(),
                'message' => 'All records'
            ], 200); 
        }

        if (!empty($this->searchableColumns) && !empty($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    if (strpos($column, '.') !== false) {
                        $relationship = explode('.', $column);
                        // OPTION FOR Deeper Nested Relationships
                        $q->orWhereHas($relationship[0], function ($q) use ($search, $relationship) {
                            $q->where($relationship[1], 'like', '%' . $search . '%');
                        });
                    } else {
                        $q->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        $results = $query->orderBy($sort, $order)->paginate($perPage);


        return $this->resource ? $this->resource::collection($results) : response()->json($results, 200);
    }

    public function thrashed(Request $request)
    {
        $this->checkProperties(2);

        $perPage = $request->per_page ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';
        $search = $request->search ?? '';
        if ($search !== '') {
            $request->merge(['page' => 1]);
        }

        $query = $this->model::onlyTrashed();

        if (!empty($this->searchableColumns) && !empty($search)) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $results = $query->orderBy($sort, $order)->paginate($perPage);

        return $this->resource ? $this->resource::collection($results) : response()->json($results, 200);
    }

    public function all(Request $request)
    {
        $this->checkProperties(2);

        $perPage = $request->per_page ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';
        $search = $request->search ?? '';
        if ($search !== '') {
            $request->merge(['page' => 1]);
        }

        $query = $this->model::withTrashed();

        if (!empty($this->searchableColumns) && !empty($search)) {
            $query->where(function ($q) use ($search) {
                foreach ($this->searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $results = $query->orderBy($sort, $order)->paginate($perPage);

        return $this->resource ? $this->resource::collection($results) : response()->json($results, 200);
    }

    public function show($id)
    {
        $this->checkProperties(2);
        $isSlug = request()->is_slug ?? false;
        try {
            $model = $isSlug ? $this->model::where('slug', $id)->firstOrFail() : $this->model::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Record not found',
                'isSlug' => $isSlug,
            ], 404);
        }
        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }

    public function store(Request $request)
    {
        $this->checkProperties();

        $validData = !empty($this->rules) ? $request->validate($this->rules) : $request->all();
        $model = $this->model::create($validData);

        return $this->resource ? new $this->resource($model) : response()->json($model, 201);
    }

    public function update(Request $request, $id)
    {
        $this->checkProperties();

        $validData = !empty($this->rules) ? $request->validate($this->rules) : $request->all();
        $model = $this->model::findOrFail($id);
        $model->update($validData);

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
    }

    public function destroy($id)
    {
        $this->checkProperties(2);
        $ids = str_contains($id, ',') ? explode(',', $id) : [$id];
        $ids = is_array($ids) ? $ids : [$ids];
        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        $responseMessages = [];
    
        foreach ($ids as $singleId) {
            $model = $this->model::findOrFail($singleId);
            // dont delete published records
            if ($model->status == 'published') {
                $responseMessages[] = "Record with ID $singleId is published and cannot be deleted.";
            }
            else if (!$isAdmin) {
                $model->update(['status' => 'pending delete']);
                $responseMessages[] = "Record with ID $singleId is pending for deletion.";
            } else {
                $model->update(['status' => 'draft']);
                $model->delete();
                $responseMessages[] = "Record with ID $singleId has been deleted.";
            }
        }
    
        return response()->json(['messages' => $responseMessages], 200);
    }

    public function restore($id)
    {
        $this->checkProperties(2);
    
        $ids = str_contains($id, ',') ? explode(',', $id) : [$id];
        $ids = is_array($ids) ? $ids : [$ids];
        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        $responseMessages = [];
    
        foreach ($ids as $singleId) {
            $model = $this->model::withTrashed()->findOrFail($singleId);
    
            if (!$isAdmin) {
                $model->update(['status' => 'pending restore']);
                $responseMessages[] = "Record with ID $singleId is pending for restore.";
            } else {
                $model->update(['status' => 'draft']);
                $model->restore();
                $responseMessages[] = "Record with ID $singleId has been restored.";
            }
        }
    
        return response()->json(['messages' => $responseMessages], 200);
    }

    public function forceDelete()
    {
        $this->checkProperties(2);
        $ids = request()->ids;
        $ids = is_array($ids) ? $ids : [$ids];
        $responseMessages = [];

        foreach ($ids as $singleId) {
            $model = $this->model::withTrashed()->findOrFail($singleId);
            $model->forceDelete();
            $responseMessages[] = "Record with ID $singleId has been permanently deleted.";
        }

        return response()->json(['messages' => $responseMessages], 200);

    }

    
    public function returnRequest($request)
    {
        return $request->all();
    }

    public function returnValidation($request)
    {
        return $request->validate($this->rules);
    }

    public function customResponse($data, $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => 'Custom response'
        ], $status);
    }

    
    public function export(string $id, string $type)
    {
        $this->checkProperties(2);
        if (is_null($this->ExportClass)) {
            throw new \Exception('Error: Not Implemented. Export class is not set.');
        }
        if (!in_array($type, ['xls', 'xlsx', 'csv'])) {
            throw new \Exception('Invalid export type.');
        }

        $fileType = \Maatwebsite\Excel\Excel::XLSX;
        $fileName = app($this->model)->getTable() . '-' . date('Y-m-d-H-i-s');
        if ($type == 'csv') {
            $fileType = \Maatwebsite\Excel\Excel::CSV;
            $fileName .= '.csv';
        }
        else {
            $fileName .= '.xlsx';
        }
        

        return Excel::download(new $this->ExportClass($id), $fileName, $fileType);;

    }

}