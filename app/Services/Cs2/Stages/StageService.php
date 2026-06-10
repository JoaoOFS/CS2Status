<?php

namespace App\Services\Cs2\Stages;

use App\Models\Cs2\Stage;
use App\Repositories\Cs2\Contracts\StageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StageService
{
    public function __construct(private readonly StageRepositoryInterface $stages)
    {
    }

    public function find(int $id): ?Stage
    {
        return $this->stages->find($id);
    }

    public function listByEvent(int $eventId, array $filters = []): Collection
    {
        return $this->stages->listByEvent($eventId, $filters);
    }
}
