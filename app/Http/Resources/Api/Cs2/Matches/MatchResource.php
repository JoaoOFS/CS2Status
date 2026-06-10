<?php

namespace App\Http\Resources\Api\Cs2\Matches;

use App\Http\Resources\Api\Cs2\Teams\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'stage_id' => $this->stage_id,
            'source' => $this->source,
            'external_id' => $this->external_id,
            'round' => $this->round,
            'record_group' => $this->record_group,
            'best_of' => $this->best_of,
            'status' => $this->status,
            'starts_at' => $this->starts_at?->toJSON(),
            'team_one' => new TeamResource($this->whenLoaded('teamOne')),
            'team_two' => new TeamResource($this->whenLoaded('teamTwo')),
            'winner_team' => new TeamResource($this->whenLoaded('winnerTeam')),
            'team_one_score' => $this->team_one_score,
            'team_two_score' => $this->team_two_score,
            'maps' => MatchMapResource::collection($this->whenLoaded('maps')),
            'metadata' => $this->metadata,
        ];
    }
}
