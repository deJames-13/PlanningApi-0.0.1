<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'annual' => $this->annual->map(function ($annual) {
                return [
                    ...$annual->toArray(),
                    'quarters' => $annual->quarter
                ];
            }),
            'sector' => $this->sector_id? $this->sector : null,
        ];
    }
}
