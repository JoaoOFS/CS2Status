<?php

namespace App\Http\Resources\Api\Cs2\Stages;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'source' => $this->source,
            'external_id' => $this->external_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'format' => $this->format,
            'status' => $this->status,
            'starts_at' => $this->starts_at?->toJSON(),
            'ends_at' => $this->ends_at?->toJSON(),
            'metadata' => $this->metadata,
        ];
    }
}
