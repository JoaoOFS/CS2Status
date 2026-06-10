<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\StageTeam;
use App\Repositories\Cs2\DTOs\StageTeamData;
use Illuminate\Database\Eloquent\Collection;

interface StageTeamRepositoryInterface
{
    public function listByStage(int $stageId): Collection;

    public function findByStageAndTeam(int $stageId, int $teamId): ?StageTeam;

    public function upsertByExternalId(StageTeamData $data): StageTeam;

    public function updateRecord(StageTeam $stageTeam, int $wins, int $losses, string $record, string $status): StageTeam;
}
