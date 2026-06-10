<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\MatchModel;
use App\Repositories\Cs2\DTOs\MatchData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface MatchRepositoryInterface
{
    public function find(int $id): ?MatchModel;

    public function paginateByStage(int $stageId, array $filters = [], int $perPage = 25): LengthAwarePaginator;

    public function completedByStage(int $stageId): Collection;

    public function upsertByExternalId(MatchData $data): MatchModel;
}
