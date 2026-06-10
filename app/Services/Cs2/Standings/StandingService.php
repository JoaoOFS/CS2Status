<?php

namespace App\Services\Cs2\Standings;

use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use Illuminate\Support\Collection;

class StandingService
{
    public function __construct(private readonly StageTeamRepositoryInterface $stageTeams)
    {
    }

    public function showByStage(int $stageId): Collection
    {
        return $this->stageTeams->listByStage($stageId)
            ->groupBy('record')
            ->map(fn ($teams, string $record): array => [
                'record' => $record,
                'status' => $teams->first()->status,
                'teams' => $teams->values(),
            ])
            ->values();
    }
}
