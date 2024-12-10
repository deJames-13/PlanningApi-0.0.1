<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ObjectiveResource;
use App\Http\Resources\BudgetResource;

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
            'objectives' => ObjectiveResource::collection($this->whenLoaded('objectives')),
            'budgets' => BudgetResource::collection($this->whenLoaded('budgets')),
        ];
    }
}
