<?php

namespace App\Services\Cs2\Matches;

use App\Models\Cs2\MatchModel;
use App\Repositories\Cs2\Contracts\MatchRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MatchService
{
    public function __construct(private readonly MatchRepositoryInterface $matches)
    {
    }

    public function find(int $id): ?MatchModel
    {
        return $this->matches->find($id);
    }

    public function paginateByStage(int $stageId, array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        return $this->matches->paginateByStage($stageId, $filters, $perPage);
    }
}
