<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

// General Controller class
abstract class Controller
{
    protected $model = null;
    protected $resource = null;
    protected $rules = [];
    protected $messages = [];
    protected $isApiResource = true;
    protected $searchableColumns = [];

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

        $perPage = $request->per_page ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';
        $search = $request->search ?? '';

        $query = $this->model::query();

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

    public function thrashed(Request $request)
    {
        $this->checkProperties(2);

        $perPage = $request->per_page ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';
        $search = $request->search ?? '';

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
        try {
            $model = $this->model::findOrFail($id);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Record not found'], 404);
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

        $model = $this->model::findOrFail($id);
        $model->delete();

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $this->checkProperties(2);

        $model = $this->model::withTrashed()->findOrFail($id);
        $model->restore();

        return $this->resource ? new $this->resource($model) : response()->json($model, 200);
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

}