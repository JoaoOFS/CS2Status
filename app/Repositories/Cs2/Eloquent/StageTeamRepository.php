<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\StageTeam;
use App\Repositories\Cs2\Contracts\StageTeamRepositoryInterface;
use App\Repositories\Cs2\DTOs\StageTeamData;
use Illuminate\Database\Eloquent\Collection;

class StageTeamRepository implements StageTeamRepositoryInterface
{
    public function listByStage(int $stageId): Collection
    {
        return StageTeam::query()
            ->with('team')
            ->where('stage_id', $stageId)
            ->orderByDesc('wins')
            ->orderBy('losses')
            ->orderBy('record')
            ->get();
    }

    public function findByStageAndTeam(int $stageId, int $teamId): ?StageTeam
    {
        return StageTeam::query()
            ->where('stage_id', $stageId)
            ->where('team_id', $teamId)
            ->first();
    }

    public function upsertByExternalId(StageTeamData $data): StageTeam
    {
        return StageTeam::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }

    public function updateRecord(StageTeam $stageTeam, int $wins, int $losses, string $record, string $status): StageTeam
    {
        $stageTeam->update([
            'wins' => $wins,
            'losses' => $losses,
            'record' => $record,
            'status' => $status,
        ]);

        return $stageTeam->refresh();
    }
}
