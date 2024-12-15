<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ParticularResource;

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
            'particulars' => $this->whenLoaded('particulars', ParticularResource::collection($this->particular)),
            
        ];
    }
}
