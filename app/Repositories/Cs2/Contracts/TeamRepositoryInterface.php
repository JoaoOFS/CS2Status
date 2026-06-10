<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\Team;
use App\Repositories\Cs2\DTOs\TeamData;

interface TeamRepositoryInterface
{
    public function find(int $id): ?Team;

    public function upsertByExternalId(TeamData $data): Team;
}
