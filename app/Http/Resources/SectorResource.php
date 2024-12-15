<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\BudgetResource;
use App\Http\Resources\DepartmentResource;

class SectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'objectives' => $this->whenLoaded('objectives', ObjectiveResource::collection($this->objectives)),
            'budgets' => $this->whenLoaded('budgets', BudgetResource::collection($this->budgets)),
            'department' => $this->whenLoaded('department', new DepartmentResource($this->department)),


        ];
    }
}
