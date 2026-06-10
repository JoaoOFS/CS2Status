<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\MatchMap;
use App\Repositories\Cs2\DTOs\MatchMapData;
use Illuminate\Database\Eloquent\Collection;

interface MatchMapRepositoryInterface
{
    public function listByMatch(int $matchId): Collection;

    public function upsertByExternalId(MatchMapData $data): MatchMap;
}
