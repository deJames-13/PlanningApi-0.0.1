<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'department' => $this->department ? $this->department->only(['id', 'name']) : null,
            'objectives' => $this->objectives ? $this->objectives->map(function ($objective) {
                return $objective->only(['id', 'title']);
            }) : null,
            'budgets' => $this->budgets ? $this->budgets->map(function ($budget) {
                return $budget->only(['id', 'title']);
            }) : null,
        ];
    }
}
