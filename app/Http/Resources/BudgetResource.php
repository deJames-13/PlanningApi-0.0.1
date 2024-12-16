<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AnnualBudgetResource;

class BudgetResource extends JsonResource
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
            'annual' => $this->whenLoaded('annual', fn() => AnnualBudgetResource::collection($this->annual)),
            'sector' => $this->sector ? $this->sector->only('id', 'name', 'slug') : null,
        ];
    }
}
