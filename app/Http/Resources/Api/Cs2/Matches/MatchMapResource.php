<?php

namespace App\Http\Resources\Api\Cs2\Matches;

use App\Http\Resources\Api\Cs2\Teams\TeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchMapResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order' => $this->order,
            'map_name' => $this->map_name,
            'winner_team' => new TeamResource($this->whenLoaded('winnerTeam')),
            'team_one_score' => $this->team_one_score,
            'team_two_score' => $this->team_two_score,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
