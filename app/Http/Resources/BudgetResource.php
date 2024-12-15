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
            'sector' => $this->sector_id? $this->sector : null,
            'annual' => $this->whenLoaded('annual', fn() => AnnualBudgetResource::collection($this->annual)),
        ];
    }
}
