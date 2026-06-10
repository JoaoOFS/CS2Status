<?php

namespace App\Services\Cs2\Events;

use App\Models\Cs2\Event;
use App\Repositories\Cs2\Contracts\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EventService
{
    public function __construct(private readonly EventRepositoryInterface $events)
    {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->events->paginate($filters, $perPage);
    }

    public function find(int $id): ?Event
    {
        return $this->events->find($id);
    }
}
