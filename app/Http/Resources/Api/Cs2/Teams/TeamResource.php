<?php

namespace App\Http\Resources\Api\Cs2\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source,
            'external_id' => $this->external_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'country' => $this->country,
            'logo_url' => $this->logo_url,
            'metadata' => $this->metadata,
        ];
    }
}
