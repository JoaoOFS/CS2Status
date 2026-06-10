<?php

namespace App\Http\Resources\Api\Cs2\Standings;

use App\Http\Resources\Api\Cs2\Teams\StageTeamResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StandingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'record' => $this->resource['record'],
            'status' => $this->resource['status'],
            'teams' => StageTeamResource::collection($this->resource['teams']),
        ];
    }
}
