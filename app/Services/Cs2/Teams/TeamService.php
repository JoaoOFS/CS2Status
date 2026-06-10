<?php

namespace App\Services\Cs2\Teams;

use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    public function __construct(private readonly StageTeamRepositoryInterface $stageTeams)
    {
    }

    public function listByStage(int $stageId): Collection
    {
        return $this->stageTeams->listByStage($stageId);
    }
}
