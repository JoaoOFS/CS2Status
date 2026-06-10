<?php

namespace App\Http\Resources\Api\Cs2\Teams;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageTeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stage_id' => $this->stage_id,
            'team' => new TeamResource($this->whenLoaded('team')),
            'wins' => $this->wins,
            'losses' => $this->losses,
            'record' => $this->record,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
