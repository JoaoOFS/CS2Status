<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\Stage;
use App\Repositories\Cs2\DTOs\StageData;
use Illuminate\Database\Eloquent\Collection;

interface StageRepositoryInterface
{
    public function find(int $id): ?Stage;

    public function listByEvent(int $eventId, array $filters = []): Collection;

    public function upsertByExternalId(StageData $data): Stage;
}
