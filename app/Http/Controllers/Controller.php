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
    protected $with = [];

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
        $with = $request->with ?? false;



        
        // if with has length > 0
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
        $model = $this->model::findOrFail($id);

        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        if (!$isAdmin) {
            $model->update(['status' => 'pending delete']);
            return response()->json(['message' => 'Record is pending for deletion.'], 200);
        } 
        $model->delete();

        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $this->checkProperties(2);

        $model = $this->model::withTrashed()->findOrFail($id);
        $isAdmin = request()->user()->hasRole(['admin', 'super-admin']);
        if (!$isAdmin) {
            $model->update(['status' => 'pending restore']);
            return response()->json(['message' => 'Record is pending for restore.'], 200);
        } 

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