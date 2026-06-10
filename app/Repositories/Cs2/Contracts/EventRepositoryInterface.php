<?php

namespace App\Repositories\Cs2\Contracts;

use App\Models\Cs2\Event;
use App\Repositories\Cs2\DTOs\EventData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Event;

    public function listActive(): Collection;

    public function upsertByExternalId(EventData $data): Event;
}
