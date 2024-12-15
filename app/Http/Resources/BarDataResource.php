<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarDataResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'particulars' => $this->particular->map(function ($particular) {
                return [
                    'id' => $particular->id,
                    'title' => $particular->title,
                    'description' => $particular->description,
                    'type' => $particular->type,
                    'values' => $particular->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'year' => $value->year,
                            'target' => $value->target,
                            'accomplishment' => $value->accomplishment,
                        ];
                    }),
                ];
            }),
        ];
    }
}
