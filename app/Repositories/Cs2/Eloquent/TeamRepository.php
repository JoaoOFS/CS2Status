<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\Team;
use App\Repositories\Cs2\Contracts\TeamRepositoryInterface;
use App\Repositories\Cs2\DTOs\TeamData;

class TeamRepository implements TeamRepositoryInterface
{
    public function find(int $id): ?Team
    {
        return Team::query()->find($id);
    }

    public function upsertByExternalId(TeamData $data): Team
    {
        return Team::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }
}
