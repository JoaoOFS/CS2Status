<?php

namespace App\Http\Resources\Api\Cs2\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'external_id' => $this->external_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'region' => $this->region,
            'starts_on' => $this->starts_on?->toDateString(),
            'ends_on' => $this->ends_on?->toDateString(),
            'metadata' => $this->metadata,
        ];
    }
}
