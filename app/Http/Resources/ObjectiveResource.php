<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectiveResource extends JsonResource
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
            'quarters' => $this->quarters,
            'total' => $this->getTotal(),
            'sector' => $this->sector ? $this->sector->only('id', 'name', 'slug') : null,
        ];
    }
}
