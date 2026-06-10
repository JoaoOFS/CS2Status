<?php

namespace App\Repositories\Cs2\Eloquent;

use App\Models\Cs2\MatchModel;
use App\Repositories\Cs2\Contracts\MatchRepositoryInterface;
use App\Repositories\Cs2\DTOs\MatchData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MatchRepository implements MatchRepositoryInterface
{
    public function find(int $id): ?MatchModel
    {
        return MatchModel::query()
            ->with(['event', 'stage', 'teamOne', 'teamTwo', 'winnerTeam', 'maps.winnerTeam'])
            ->find($id);
    }

    public function paginateByStage(int $stageId, array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        return MatchModel::query()
            ->with(['teamOne', 'teamTwo', 'winnerTeam'])
            ->where('stage_id', $stageId)
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['round'] ?? null, fn ($query, int $round) => $query->where('round', $round))
            ->when($filters['record_group'] ?? null, fn ($query, string $recordGroup) => $query->where('record_group', $recordGroup))
            ->orderByRaw('starts_at is null')
            ->orderBy('starts_at')
            ->orderBy('round')
            ->paginate($perPage);
    }

    public function completedByStage(int $stageId): Collection
    {
        return MatchModel::query()
            ->where('stage_id', $stageId)
            ->where('status', 'completed')
            ->whereNotNull('winner_team_id')
            ->orderBy('starts_at')
            ->get();
    }

    public function upsertByExternalId(MatchData $data): MatchModel
    {
        return MatchModel::query()->updateOrCreate(
            ['source' => $data->source, 'external_id' => $data->externalId],
            $data->toArray(),
        );
    }
}
