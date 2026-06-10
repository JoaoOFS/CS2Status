<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\MatchMap;
use App\Repositories\Cs2\Contracts\MatchMapRepositoryInterface;
use App\Repositories\Cs2\DTOs\MatchMapData;
use Illuminate\Database\Eloquent\Collection;

class MatchMapRepository implements MatchMapRepositoryInterface
{
    public function listByMatch(int $matchId): Collection
    {
        return MatchMap::query()
            ->with('winnerTeam')
            ->where('match_id', $matchId)
            ->orderBy('order')
            ->get();
    }

    public function upsertByExternalId(MatchMapData $data): MatchMap
    {
        return MatchMap::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }
}
